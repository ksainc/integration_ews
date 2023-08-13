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

namespace OCA\EWS\Service;

use DateTime;
use Exception;
use Throwable;
use Psr\Log\LoggerInterface;
use OCA\DAV\CardDAV\CardDavBackend;
use OCA\DAV\CalDAV\CalDavBackend;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Db\Action;
use OCA\EWS\Db\ActionMapper;
use OCA\EWS\Service\ConfigurationService;
use OCA\EWS\Service\CoreService;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\ContactsService;
use OCA\EWS\Service\EventsService;
use OCA\EWS\Service\TasksService;
use OCA\EWS\Service\Local\LocalContactsService;
use OCA\EWS\Service\Local\LocalEventsService;
use OCA\EWS\Service\Local\LocalTasksService;
use OCA\EWS\Service\Remote\RemoteContactsService;
use OCA\EWS\Service\Remote\RemoteEventsService;
use OCA\EWS\Service\Remote\RemoteTasksService;
use OCA\EWS\Service\Remote\RemoteCommonService;
use OCA\EWS\Tasks\HarmonizationLauncher;

class HarmonizationService {

	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var INotificationManager
	 */
	private $notificationManager;
	/**
	 * @var ConfigurationService
	 */
	private $ConfigurationService;
	/**
	 * @var CoreService
	 */
	private $CoreService;
	/**
	 * @var CorrelationsService
	 */
	private $CorrelationsService;
	/**
	 * @var LocalContactsService
	 */
	private $LocalContactsService;
	/**
	 * @var LocalEventsService
	 */
	private $LocalEventsService;
	/**
	 * @var LocalTasksService
	 */
	private $LocalTasksService;
	/**
	 * @var RemoteContactsService
	 */
	private $RemoteContactsService;
	/**
	 * @var RemoteEventsService
	 */
	private $RemoteEventsService;
	/**
	 * @var RemoteTasksService
	 */
	private $RemoteTasksService;
	/**
	 * @var CardDavBackend
	 */
	private $LocalContactsStore;
	/**
	 * @var CalDavBackend
	 */
	private $LocalEventsStore;
	/**
	 * @var EWSClient
	 */
	private $RemoteStore;
	

	public function __construct (string $appName,
								LoggerInterface $logger,
								ActionMapper $ActionManager,
								ConfigurationService $ConfigurationService,
								CoreService $CoreService,
								CorrelationsService $CorrelationsService,
								LocalContactsService $LocalContactsService,
								LocalEventsService $LocalEventsService,
								LocalTasksService $LocalTasksService,
								RemoteContactsService $RemoteContactsService,
								RemoteEventsService $RemoteEventsService,
								RemoteTasksService $RemoteTasksService,
								RemoteCommonService $RemoteCommonService,
								ContactsService $ContactsService,
								EventsService $EventsService,
								TasksService $TasksService,
								CardDavBackend $LocalContactsStore,
								CalDavBackend $LocalEventsStore) {
		$this->logger = $logger;
		$this->ConfigurationService = $ConfigurationService;
		$this->CoreService = $CoreService;
		$this->CorrelationsService = $CorrelationsService;
		$this->LocalContactsService = $LocalContactsService;
		$this->LocalEventsService = $LocalEventsService;
		$this->LocalTasksService = $LocalTasksService;
		$this->RemoteContactsService = $RemoteContactsService;
		$this->RemoteEventsService = $RemoteEventsService;
		$this->RemoteTasksService = $RemoteTasksService;
		$this->RemoteCommonService = $RemoteCommonService;
		$this->ContactsService = $ContactsService;
		$this->EventsService = $EventsService;
		$this->TasksService = $TasksService;
		$this->LocalContactsStore = $LocalContactsStore;
		$this->LocalEventsStore = $LocalEventsStore;
		$this->ActionManager = $ActionManager;

	}

	/**
	 * Perform harmonization for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $mode	running mode (S - Service, M - Manually)
	 *
	 * @return void
	 */
	public function performHarmonization(string $uid, string $mode = "S"): void {

		$this->logger->info('Statred Harmonization of Collections for ' . $uid);

		// evaluate, if harmonization is executing
		if ($this->ConfigurationService->getHarmonizationState($uid) === true) {
			if ((time() - $this->ConfigurationService->getHarmonizationHeartBeat($uid)) < 900) {
				return;
			}
		}

		// update harmonization state and start time
		$this->ConfigurationService->setHarmonizationState($uid, true);
		$this->ConfigurationService->setHarmonizationStart($uid);
		$this->ConfigurationService->setHarmonizationHeartBeat($uid);
		
		try {
			// retrieve Configuration
			$Configuration = $this->ConfigurationService->retrieveUser($uid);
			$Configuration = $this->ConfigurationService->toUserConfigurationObject($Configuration);
			// create remote store client
			$RemoteStore = $this->CoreService->createClient($uid);
			// contacts harmonization
			if ($this->ConfigurationService->isContactsAppAvailable() &&
				(($mode === 'S' && $Configuration->ContactsHarmonize > 0) ||
				($mode === 'M' && $Configuration->ContactsHarmonize > -1))) {
				$this->ContactsService->RemoteStore = $RemoteStore;
				// retrieve list of collections correlations
				$correlations = $this->CorrelationsService->findByType($uid, 'CC');
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// execute contacts harmonization loop
					do {
						// update harmonization heart beat
						$this->ConfigurationService->setHarmonizationHeartBeat($uid);
						// harmonize contacts collections
						$statistics = $this->ContactsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
				}
			}
			// events harmonization
			if ($this->ConfigurationService->isCalendarAppAvailable() &&
				(($mode === 'S' && $Configuration->EventsHarmonize > 0) ||
				($mode === 'M' && $Configuration->EventsHarmonize > -1))) {
				$this->EventsService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, 'EC');
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// execute events harmonization loop
					do {
						// update harmonization heart beat
						$this->ConfigurationService->setHarmonizationHeartBeat($uid);
						// harmonize events collections
						$statistics = $this->EventsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
				}
			}
			// tasks harmonization
			if ($this->ConfigurationService->isTasksAppAvailable() &&
				(($mode === 'S' && $Configuration->TasksHarmonize > 0) ||
				($mode === 'M' && $Configuration->TasksHarmonize > -1))) {
				$this->TasksService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, 'TC');
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// execute tasks harmonization loop
					do {
						// update harmonization heart beat
						$this->ConfigurationService->setHarmonizationHeartBeat($uid);
						// harmonize tasks collections
						$statistics = $this->TasksService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'tasks_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
				}
			}
		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}
		// update harmonization state and end time
		$this->ConfigurationService->setHarmonizationState($uid, false);
		$this->ConfigurationService->setHarmonizationEnd($uid);

		$this->logger->info('Finished Harmonization of Collections for ' . $uid);
	}

	/**
	 * Perform actions for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 *
	 * @return void
	 */
	public function performActions (string $uid): void {

		// retrieve harmonization status
		$ttl = $this->ConfigurationService->getHarmonizationStart($uid);

		try {
			// retrieve Configuration
			$Configuration = $this->ConfigurationService->retrieveUser($uid);
			$Configuration = $this->ConfigurationService->toUserConfigurationObject($Configuration);
			// create remote store client
			$RemoteStore = $this->CoreService->createClient($uid);
			// contacts harmonization
			if ($this->ConfigurationService->isContactsAppAvailable() && $Configuration->ContactsHarmonize > -1) {
				$this->ContactsService->RemoteStore = $RemoteStore;
				// update harmonization heart beat
				$this->ConfigurationService->setHarmonizationHeartBeat($uid);
				// harmonize contact collections
				$statistics = $this->ContactsService->performActions($ttl, $Configuration);
				// evaluate if anything was done and publish notice if needed
				if ($statistics->total() > 0) {
					$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
				}
			}
			// events harmonization
			if ($this->ConfigurationService->isCalendarAppAvailable() && $Configuration->EventsHarmonize > -1) {
				$this->EventsService->RemoteStore = $RemoteStore;
				// update harmonization heart beat
				$this->ConfigurationService->setHarmonizationHeartBeat($uid);
				// harmonize event collections
				$statistics = $this->EventsService->performActions($ttl, $Configuration);
				// evaluate if anything was done and publish notice if needed
				if ($statistics->total() > 0) {
					$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
				}
			}
			// tasks harmonization
			if ($this->ConfigurationService->isTasksAppAvailable() && $Configuration->TasksHarmonize > -1) {
				$this->TasksService->RemoteStore = $RemoteStore;
				// update harmonization heart beat
				$this->ConfigurationService->setHarmonizationHeartBeat($uid);
				// harmonize tasks collections
				$statistics = $this->TasksService->performActions($ttl, $Configuration);
				// evaluate if anything was done and publish notice if needed
				if ($statistics->total() > 0) {
					$this->CoreService->publishNotice($uid,'tasks_harmonized', (array)$statistics);
				}
			}
		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}
		
	}
	
	public function connectEvents(string $uid, int $duration, string $ctype): ?object {

		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);
		// retrieve correlations
		$cc = $this->CorrelationsService->findByType($uid, $ctype);
		// extract correlation ids
		$ids = array_map(function($o) { return $o->getroid();}, $cc);
		// execute command
		$rs = $this->RemoteCommonService->connectEvents($RemoteStore, $duration, $ids);
		// return id and token
		if ($rs instanceof \stdClass)
		{
			return $rs;
		}
		else {
			return null;
		}

	}

	public function disconnectEvents(string $uid, string $id): ?bool {

		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);
		// execute command
		$rs = $this->RemoteCommonService->disconnectEvents($RemoteStore, $id);
		// return response
		return $rs;

	}

	public function consumeEvents(string $uid, string $id, string $token, string $otype): ?object {

		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);
		// execute command
		$rs = $this->RemoteCommonService->fetchEvents($RemoteStore, $id, $token);

		// top date time in epoch
		$tt = 0;
		
		if (isset($rs->CreatedEvent)) {
			foreach ($rs->CreatedEvent as $entry) {
				// evaluate, if it was an object event, ignore collection events
				if (isset($entry->ItemId)) {
					// extract atributes
					$cid = $entry->ParentFolderId->Id;
					$oid = $entry->ItemId->Id;
					$ostate = $entry->ItemId->ChangeKey;
					// retrieve object corrollation
					$ci = $this->CorrelationsService->findByRemoteId($uid, $otype, $oid, $cid);
					// work around to filter out harmonization generated events
					// evaluate corrollation, if dose not exists or state does not match, create action
					if (!($ci instanceof \OCA\EWS\Db\Correlation) || $ci->getrostate() != $ostate) {
						// construct action entry
						$a = new Action();
						$a->setuid($uid);
						$a->settype($otype);
						$a->setaction('C');
						$a->setorigin('R');
						$a->setrcid($cid);
						$a->setroid($oid);
						$a->setrostate($ostate);
						$a->setcreatedon($entry->TimeStamp);
						// deposit action entry
						$this->ActionManager->insert($a);
					}
					// workaround to find newest token
					// convert date/time to epoch time
					$et = strtotime($entry->TimeStamp);
					// evaluate, if event time is greatest
					if ($et !== false && $et > $tt) {
						$tt = $et;
						$token = $entry->Watermark;
					}
				}
			}
		}

		if (isset($rs->ModifiedEvent)) {
			foreach ($rs->ModifiedEvent as $entry) {
				// evaluate, if it was an object event, ignore collection events
				if (isset($entry->ItemId)) {
					// extract atributes
					$cid = $entry->ParentFolderId->Id;
					$oid = $entry->ItemId->Id;
					$ostate = $entry->ItemId->ChangeKey;
					// retrieve object corrollation
					$ci = $this->CorrelationsService->findByRemoteId($uid, $otype, $oid, $cid);
					// work around to filter out harmonization generated events
					// evaluate corrollation, if dose not exists or state does not match, create action
					if (!($ci instanceof \OCA\EWS\Db\Correlation) || $ci->getrostate() != $ostate) {
						// construct action entry
						$a = new Action();
						$a->setuid($uid);
						$a->settype($otype);
						$a->setaction('U');
						$a->setorigin('R');
						$a->setrcid($cid);
						$a->setroid($oid);
						$a->setrostate($ostate);
						$a->setcreatedon($entry->TimeStamp);
						// deposit action entry
						$this->ActionManager->insert($a);
					}
					// workaround to find newest token
					// convert date/time to epoch time
					$et = strtotime($entry->TimeStamp);
					// evaluate, if event time is greatest
					if ($et !== false && $et > $tt) {
						$tt = $et;
						$token = $entry->Watermark;
					}
				}
			}
		}

		if (isset($rs->DeletedEvent)) {
			foreach ($rs->DeletedEvent as $entry) {
				// evaluate, if it was an object event, ignore collection events
				if (isset($entry->ItemId)) {
					// extract atributes
					$cid = $entry->ParentFolderId->Id;
					$oid = $entry->ItemId->Id;
					$ostate = $entry->ItemId->ChangeKey;
					// retrieve object corrollation
					$ci = $this->CorrelationsService->findByRemoteId($uid, $otype, $oid, $cid);
					// work around to filter out harmonization generated events
					// evaluate corrollation, if dose not exists or state does not match, create action
					if ($ci instanceof \OCA\EWS\Db\Correlation) {
						// construct action entry
						$a = new Action();
						$a->setuid($uid);
						$a->settype($otype);
						$a->setaction('D');
						$a->setorigin('R');
						$a->setrcid($cid);
						$a->setroid($oid);
						$a->setrostate($ostate);
						$a->setcreatedon($entry->TimeStamp);
						// deposit action entry
						$this->ActionManager->insert($a);
					}
					// workaround to find newest token
					// convert date/time to epoch time
					$et = strtotime($entry->TimeStamp);
					// evaluate, if event time is greatest
					if ($et !== false && $et > $tt) {
						$tt = $et;
						$token = $entry->Watermark;
					}
				}
			}
		}

		// return response
		return (object) ['Id' => $id, 'Token' => $token];

	}

}
