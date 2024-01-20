<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\EWS\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use OCP\IUserManager;

use OCA\EWS\Service\CoreService;
use OCA\EWS\Service\ConfigurationService;

class Correlate extends Command {

	private $_ConfigurationService;
	private $_CoreService;
	private $_RemoteService;
	private $_LocalService;

	private $_Module;
	private $_Configuration;
	private $_RemoteStore;
	private $_LocalStore;

	public function __construct(IUserManager $userManager, ConfigurationService $ConfigurationService, CoreService $CoreService) {
		parent::__construct();
        $this->userManager = $userManager;
		$this->_ConfigurationService = $ConfigurationService;
        $this->_CoreService = $CoreService;
	}

	protected function configure() {
		$this
			->setName('ews:correlate')
			->setDescription('Creates a correlation between collection on local and remote systems')
            ->addArgument(
				'user',
				InputArgument::REQUIRED,
				'User whom to create correlation for'
			)
			->addArgument(
				'module',
				InputArgument::REQUIRED,
				'Collection(s) to correlate (Contacts, Events, Tasks)'
			)
			->addOption(
				'remote',
				null,
				InputOption::VALUE_REQUIRED,
				'Name of remote collection'
			)
			->addOption(
				'local',
				null,
				InputOption::VALUE_REQUIRED,
				'Name of local collection'
			)
			->addOption(
				'force-match',
				null,
				InputOption::VALUE_NONE,
				'Forces an attempt to match by simularity, if either the remote or local collection name, is not provided or direct name match fails'
			)
			->addOption(
				'force-create',
				null,
				InputOption::VALUE_NONE,
				'Forces the creation of a collection if either the remote or local collection is missing'
			);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
		
		// retrieve arrguments
        $uid = $input->getArgument('user');
        $module = strtolower($input->getArgument('module'));
        $remote = (string) ($input->getOption('remote')) ? $input->getOption('remote') : '';
		$local = (string) ($input->getOption('local')) ? $input->getOption('local') : '';
		$match = ($input->getOption('force-match')) ? true : false;
		$create = ($input->getOption('force-create')) ? true : false;
		// evaluate if user exists
        if (!$this->userManager->userExists($uid)) {
			$output->writeln("<error>Failed: Specified user $uid does not exist</error>");
			return 1;
		}

		if (!$this->_ConfigurationService->isAccountConnected($uid)) {
			$output->writeln("<error>Failed: Specified user $uid is not connected to a remote service</error>");
			return 1;
		}

		if (array_search($module, ['contacts', 'events', 'tasks']) === false) {
			$output->writeln("<error>Failed: Invalid module specified, must be (contacts, events, tasks)</error>");
			return 1;
		}

		try {

			// define global configuration
			$this->_Module = $module;

			// retrieve Configuration
			$this->_Configuration = $this->_ConfigurationService->retrieveUser($uid);
			$this->_Configuration = $this->_ConfigurationService->toUserConfigurationObject($this->_Configuration);

			switch ($this->_Module) {
				case 'contacts':
					// create remote store client
					$this->_RemoteStore = $this->_CoreService->createClient($uid);
					// construct remote service
					$this->_RemoteService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteContactsService::class);
					// configure remote service
					$this->_RemoteService->configure($this->_Configuration, $this->_RemoteStore);
					// create local store client
					$this->_LocalStore = \OC::$server->get(\OCA\DAV\CardDAV\CardDavBackend::class);
					// construct local service
					$this->_LocalService = \OC::$server->get(\OCA\EWS\Service\Local\LocalContactsService::class);
					// configure local service
					$this->_LocalService->configure($this->_Configuration, $this->_LocalStore);
					// perform correlation
					$this->preformCorrelation($uid, $remote, $local, $match, $create);
					break;
				case 'events':
					// create remote store client
					$this->_RemoteStore = $this->_CoreService->createClient($uid);
					// construct remote service
					$this->_RemoteService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteEventsService::class);
					// configure remote service
					$this->_RemoteService->configure($this->_Configuration, $this->_RemoteStore);
					// create local store client
					$this->_LocalStore = \OC::$server->get(\OCA\DAV\CalDAV\CalDavBackend::class);
					// construct local service
					$this->_LocalService = \OC::$server->get(\OCA\EWS\Service\Local\LocalEventsService::class);
					// configure local service
					$this->_LocalService->configure($this->_Configuration, $this->_LocalStore);
					// perform correlation
					$this->preformCorrelation($uid, $remote, $local, $match, $create);
					break;
				case 'tasks':
					// create remote store client
					$this->_RemoteStore = $this->_CoreService->createClient($uid);
					// construct remote service
					$this->_RemoteService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteTasksService::class);
					// configure remote service
					$this->_RemoteService->configure($this->_Configuration, $this->_RemoteStore);
					// create local store client
					$this->_LocalStore = \OC::$server->get(\OCA\DAV\CalDAV\CalDavBackend::class);
					// construct local service
					$this->_LocalService = \OC::$server->get(\OCA\EWS\Service\Local\LocalTasksService::class);
					// configure local service
					$this->_LocalService->configure($this->_Configuration, $this->_LocalStore);
					// perform correlation
					$this->preformCorrelation($uid, $remote, $local, $match, $create);
					break;
			}
			
			$output->writeln("<info>Success: Correlation(s) Initialized</info>");
		}
		catch (\Throwable $th) {
			$output->writeln("<error>Failed: " . $th->getMessage() . "</error>");
			return 1;
		}

		return 0;

	}

	private function preformCorrelation(string $uid, string $remote, string $local, bool $match, bool $create) {
		
		if (!empty($remote) || !empty($local)) {
			$this->preformCorrelationSpecific($uid, $remote, $local, $match, $create);
		}
		elseif (empty($remote) && empty($local) && $match === true) {
			$this->preformCorrelationAny($uid, $create);
		}

	}

	private function preformCorrelationSpecific(string $uid, string $remote, string $local, bool $match, bool $create) {

		// retrieve remote and local collections
		$RemoteCollections = $this->_RemoteService->listCollections();
		$LocalCollections = $this->_LocalService->listCollections($uid, true);

		// evaluate if remote collection name was specified
		if (!empty($remote)) {
			// find remote collection id
			$rid = array_search($remote, array_column($RemoteCollections, 'name'));

			if ($rid === false) {
				throw new \Exception("Remote collection name was specified but not found on the remote system");
			}
			else {
				$rid = $RemoteCollections[$rid]['id'];
			}
		}
		elseif ($match === true && empty($remote) && !empty($local)) {
			$rid = $this->probability($local, $RemoteCollections);
		}

		// evaluate if local collection name was specified
		if (!empty($local)) {
			// find local collection id
			$lid = array_search($local, array_column($LocalCollections, 'name'));

			if ($lid === false) {
				throw new \Exception("Local collection name was specified but not found on the local system");
			}
			else {
				$lid = $LocalCollections[$lid]['id'];
			}
		}
		elseif ($match === true && empty($local) && !empty($remote)) {
			$lid = $this->probability($remote, $LocalCollections);
		}

		// evaluate, if remote collection was not found, local collection was found and create flag was set
		if ($create === true && empty($rid) && !empty($lid)) {
			// create collection
			$collection = $this->_RemoteService->createCollection('msgfolderroot', $local, true);
			// evaluate if collection id exists
			if (isset($collection->Id)) {
				$rid = $collection->Id;
			}
		}

		// evaluate, if local collection was not found, remote collection was found and create flag was set
		if ($create === true && empty($lid) && !empty($rid)) {
			// create collection
			$collection = $this->_LocalService->createCollection($uid, \OCA\EWS\Utile\UUID::v4(), $remote, true);
			// evaluate if collection id exists
			if (isset($collection->Id)) {
				$lid = $collection->Id;
			}
		}

		// evaluate if remote and local collection id's exist
		if (!empty($rid) && !empty($lid)) {
			switch ($this->_Module) {
				case 'contacts':
					$this->_CoreService->depositCorrelations(
						$uid,
						[['action' => 'C', 'roid' => $rid, 'loid' => $lid]],
						[],
						[]
					);
					break;
				case 'events':
					$this->_CoreService->depositCorrelations(
						$uid,
						[],
						[['action' => 'C', 'roid' => $rid, 'loid' => $lid]],
						[]
					);
					break;
				case 'tasks':
					$this->_CoreService->depositCorrelations(
						$uid,
						[],
						[],
						[['action' => 'C', 'roid' => $rid, 'loid' => $lid]]
					);
					break;
			}
		}
	}

	private function preformCorrelationAny(string $uid, bool $create) {

		// retrieve remote and local collections
		$RemoteCollections = $this->_RemoteService->listCollections();
		$LocalCollections = $this->_LocalService->listCollections($uid, true);

		foreach ($RemoteCollections as $entry) {
			
			// extract remote id and name
			$rid = (string) $entry['id'];
			$rname = (string) $entry['name'];
			// evaluate if id and name is not empty
			if (empty($rid) || empty($rname)) {
				// skip collection
				continue;
			}
			// attempt to match remote collection name to local collection name
			$lid = $this->probability($rname, $LocalCollections);
			// evaluate if create flag is false and local id is empty
			if ($create === false && empty($lid)) {
				// skip collection
				continue;
			}
			// evaluate if create flag is true and local id is empty
			elseif ($create === true && empty($lid)) {
				// create collection
				$collection = $this->_LocalService->createCollection($uid, \OCA\EWS\Utile\UUID::v4(), $rname, true);
				// evaluate if collection id exists
				if (isset($collection->Id)) {
					$lid = $collection->Id;
				}
			}

			// evaluate if remote and local collection id's exist
			if (!empty($rid) && !empty($lid)) {
				switch ($this->_Module) {
					case 'contacts':
						$this->_CoreService->depositCorrelations(
							$uid,
							[['action' => 'C', 'roid' => $rid, 'loid' => $lid]],
							[],
							[]
						);
						break;
					case 'events':
						$this->_CoreService->depositCorrelations(
							$uid,
							[],
							[['action' => 'C', 'roid' => $rid, 'loid' => $lid]],
							[]
						);
						break;
					case 'tasks':
						$this->_CoreService->depositCorrelations(
							$uid,
							[],
							[],
							[['action' => 'C', 'roid' => $rid, 'loid' => $lid]]
						);
						break;
				}
			}

		}

	}

	private function probability(string $needle, array $stack): string {

		$id = '';
		$best = 0;
		$percent = 0;
		foreach ($stack as $entry) {
			similar_text($needle, $entry['name'], $percent);
			if ($percent > 50 && $percent > $best) {
				$best = $percent;
				$id = (string) $entry['id'];
			}
		}

		return $id;

	}

}