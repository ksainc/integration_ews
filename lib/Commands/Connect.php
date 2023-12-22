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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use OCP\IUserManager;

use OCA\EWS\Service\CoreService;

class Connect extends Command {

	public function __construct(IUserManager $userManager, CoreService $CoreService) {
		parent::__construct();
        $this->userManager = $userManager;
        $this->CoreService = $CoreService;
	}

	protected function configure() {
		$this
			->setName('ews:connect')
			->setDescription('Connects a user to EWS Server')
            ->addArgument('user',
				InputArgument::REQUIRED,
				'User whom to connect to the EWS Service')
            ->addArgument('ServiceId',
                InputArgument::REQUIRED,
                'The username of the EWS service account to connect to')
            ->addArgument('ServiceSecret',
                InputArgument::REQUIRED,
                'The password for the EWS service account to connect to')
			->addArgument('ServiceLocation',
				InputArgument::OPTIONAL,
				'FQDN or IP address of the EWS service')
            ->addArgument('ServiceValidate',
                InputArgument::OPTIONAL,
                'Should we validate the EWS service location and credentials (default true)')
			->addArgument('ServiceVersion',
                InputArgument::OPTIONAL,
                'The EWS service version. This is required if validation is set to false. (Exchange2007, Exchange2010, Exchange2013, Exchange2016)');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
        $uid = $input->getArgument('user');
        $service_id = $input->getArgument('ServiceId');
        $service_secret = $input->getArgument('ServiceSecret');
        $service_validate = (empty($input->getArgument('ServiceValidate'))) ? true : filter_var($input->getArgument('ServiceValidate'), FILTER_VALIDATE_BOOLEAN);
		$service_location = (empty($input->getArgument('ServiceLocation'))) ? '' : $input->getArgument('ServiceLocation');
		$service_version = (empty($input->getArgument('ServiceVersion'))) ? '' : $input->getArgument('ServiceVersion');
		$flags = [];

        if (!$this->userManager->userExists($uid)) {
			$output->writeln("<error>Failed: Specified user $uid does not exist</error>");
			return 1;
		}

		if ($service_validate === true) {
			$flags[] = 'VALIDATE';
		}
		
		if ($service_validate === false && empty($service_version)) {
			$output->writeln("<error>Failed: Service version is required when validation is set to false. Possible values are Exchange2007, Exchange2007_SP1, Exchange2009, Exchange2010, Exchange2010_SP1, Exchange2010_SP2, Exchange2013, Exchange2013_SP1, Exchange2016</error>");
			return 1;
		}

		try {
			$this->CoreService->connectAccountAlternate($uid, $service_id, $service_secret, $service_location, $service_version, $flags);
			$output->writeln("<info>Success: Connected $uid to $service_location as $service_id</info>");
		}
		catch (\Throwable $th) {
			$output->writeln("<error>Failed: " . $th->getMessage() . "</error>");
			return 1;
		}

		return 0;

	}
}