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
use OCA\EWS\Service\ConfigurationService;
use OCA\EWS\Service\CoreService;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\ContactsService;
use OCA\EWS\Service\EventsService;
use OCA\EWS\Service\TasksService;
use OCA\EWS\Service\HarmonizationThreadService;
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
	

	public function __construct (string $appName,
								LoggerInterface $logger,
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
								HarmonizationThreadService $HarmonizationThreadService) {
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
		$this->HarmonizationThreadService = $HarmonizationThreadService;

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

		// update harmonization state and start time
		$this->ConfigurationService->setHarmonizationState($uid, true);
		$this->ConfigurationService->setHarmonizationStart($uid);
		$this->ConfigurationService->setHarmonizationHeartBeat($uid);		
		// retrieve Configuration
		$Configuration = $this->ConfigurationService->retrieveUser($uid);
		$Configuration = $this->ConfigurationService->toUserConfigurationObject($Configuration);
		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);

		// contacts harmonization
		try {
			// evaluate, if contacts app is available and contacts harmonization is turned on
			if ($this->ConfigurationService->isContactsAppAvailable() &&
				(($mode === 'S' && $Configuration->ContactsHarmonize > 0) ||
				($mode === 'M' && $Configuration->ContactsHarmonize > -1))) {
				$this->logger->info('Statred Harmonization of Contacts for ' . $uid);
				// assign remote data store
				$this->ContactsService->RemoteStore = $RemoteStore;
				// retrieve list of collections correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::ContactCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute contacts harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize contacts collections
						$statistics = $this->ContactsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Contacts for ' . $uid);
			}
		
		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}

		// events harmonization
		try {
			// evaluate, if calendar app is available and events harmonization is turned on
			if ($this->ConfigurationService->isCalendarAppAvailable() &&
				(($mode === 'S' && $Configuration->EventsHarmonize > 0) ||
				($mode === 'M' && $Configuration->EventsHarmonize > -1))) {
				$this->logger->info('Statred Harmonization of Events for ' . $uid);
				// assign remote data store
				$this->EventsService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::EventCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute events harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize events collections
						$statistics = $this->EventsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Events for ' . $uid);
			}
		
		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}
		
		// tasks harmonization
		try {
			// evaluate, if tasks app is available and tasks harmonization is turned on
			if ($this->ConfigurationService->isTasksAppAvailable() &&
				(($mode === 'S' && $Configuration->TasksHarmonize > 0) ||
				($mode === 'M' && $Configuration->TasksHarmonize > -1))) {
				$this->logger->info('Statred Harmonization of Tasks for ' . $uid);
				// assign remote data store
				$this->TasksService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::TaskCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute tasks harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize tasks collections
						$statistics = $this->TasksService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'tasks_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Tasks for ' . $uid);
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
	 * Perform harmonization for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 *
	 * @return void
	 */
	public function performLiveHarmonization(string $uid): void {

		$this->logger->info('Statred Live Harmonization of Collections for ' . $uid);

		// update harmonization state and start time
		$this->ConfigurationService->setHarmonizationState($uid, true);
		$this->ConfigurationService->setHarmonizationStart($uid);
		$this->ConfigurationService->setHarmonizationHeartBeat($uid);
		
		// retrieve Configuration
		$Configuration = $this->ConfigurationService->retrieveUser($uid);
		$Configuration = $this->ConfigurationService->toUserConfigurationObject($Configuration);
		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);

		// contacts harmonization
		try {
			// evaluate, if contacts app is available and contacts harmonization is turned on
			if ($this->ConfigurationService->isContactsAppAvailable() && $Configuration->ContactsHarmonize > 0) {
				$this->logger->info('Statred Harmonization of Contacts for ' . $uid);
				// assign remote data store
				$this->ContactsService->RemoteStore = $RemoteStore;
				// retrieve list of collections correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::ContactCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// evaluate, if current state is obsolete, by comparing timestamps
					if ($correlation->gethperformed() > $correlation->gethaltered()) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute contacts harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize contacts collections
						$statistics = $this->ContactsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Contacts for ' . $uid);
			}

		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}

		// events harmonization
		try {
			// evaluate, if calendar app is available and events harmonization is turned on
			if ($this->ConfigurationService->isCalendarAppAvailable() && $Configuration->EventsHarmonize > 0) {
				$this->logger->info('Statred Harmonization of Events for ' . $uid);
				// assign remote data store
				$this->EventsService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::EventCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// evaluate, if current state is obsolete, by comparing timestamps
					if ($correlation->gethperformed() > $correlation->gethaltered()) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute events harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize events collections
						$statistics = $this->EventsService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Events for ' . $uid);
			}
			

		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}

		// tasks harmonization
		try {
			// evaluate, if tasks app is available and tasks harmonization is turned on
			if ($this->ConfigurationService->isTasksAppAvailable() && $Configuration->TasksHarmonize > 0) {
				$this->logger->info('Statred Harmonization of Tasks for ' . $uid);
				// assign remote data store
				$this->TasksService->RemoteStore = $RemoteStore;
				// retrieve list of correlations
				$correlations = $this->CorrelationsService->findByType($uid, CorrelationsService::TaskCollection);
				// iterate through correlation items
				foreach ($correlations as $correlation) {
					// evaluate if correlation is locked and lock has not expired
					if ($correlation->gethlock() == 1 &&
					   (time() - $correlation->gethlockhb()) < 3600) {
						continue;
					}
					// evaluate, if current state is obsolete, by comparing timestamps
					if ($correlation->gethperformed() > $correlation->gethaltered()) {
						continue;
					}
					// lock correlation before harmonization
					$correlation->sethlock(1);
					$correlation->sethlockhd((int) getmypid());
					$this->CorrelationsService->update($correlation);
					// execute tasks harmonization loop
					do {
						// update lock heartbeat
						$correlation->sethlockhb(time());
						$this->CorrelationsService->update($correlation);
						// harmonize tasks collections
						$statistics = $this->TasksService->performHarmonization($correlation, $Configuration);
						// evaluate if anything was done and publish notice if needed
						if ($statistics->total() > 0) {
							$this->CoreService->publishNotice($uid,'tasks_harmonized', (array)$statistics);
						}
					} while ($statistics->total() > 0);
					// update harmonization time stamp
					$correlation->sethperformed(time());
					// unlock correlation after harmonization
					$correlation->sethlock(0);
					$this->CorrelationsService->update($correlation);
				}
				$this->logger->info('Finished Harmonization of Tasks for ' . $uid);
			}

		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}
		// update harmonization state and end time
		$this->ConfigurationService->setHarmonizationState($uid, false);
		$this->ConfigurationService->setHarmonizationEnd($uid);
	}

	
	public function connectEvents(string $uid, int $duration, string $ctype): ?object {

		// retrieve correlations
		$cc = $this->CorrelationsService->findByType($uid, $ctype);
		// evaluate if any correlation where found
		if (count($cc) > 0) {
			// extract correlation ids
			$ids = array_map(function($o) { return $o->getroid();}, $cc);
			// create remote store client
			$RemoteStore = $this->CoreService->createClient($uid);
			// execute command
			$rs = $this->RemoteCommonService->connectEvents($RemoteStore, $duration, $ids, null, ['CreatedEvent', 'ModifiedEvent', 'DeletedEvent', 'CopiedEvent', 'MovedEvent']);
		}
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

	public function consumeEvents(string $uid, string $id, string $token, string $ctype): ?object {

		// construct state place holder
		$state = false;
		// create remote store client
		$RemoteStore = $this->CoreService->createClient($uid);
		// execute command
		$rs = $this->RemoteCommonService->fetchEvents($RemoteStore, $id, $token);
		
		if (isset($rs->CreatedEvent)) {
			foreach ($rs->CreatedEvent as $entry) {
				// do nothing
			}
		}

		if (isset($rs->ModifiedEvent)) {
			foreach ($rs->ModifiedEvent as $entry) {
				// evaluate, if it was an collection event, ignore object events
				if (isset($entry->FolderId)) {
					// extract atributes
					$cid = $entry->FolderId->Id;
					$cstate = $entry->FolderId->ChangeKey;
					// retrieve collection correlation
					$cc = $this->CorrelationsService->findByRemoteId($uid, $ctype, $cid);
					// evaluate correlation, if exists, change altered time stamp
					if ($cc instanceof \OCA\EWS\Db\Correlation) {
						$cc->sethaltered(time());
						$this->CorrelationsService->update($cc);
						$state = true;
					}
					// aquire water mark
					$token = $entry->Watermark;
				}
				
				$w[] = ['C', ($entry->Watermark == $rs->PreviousWatermark), $entry->Watermark];
			}
		}

		if (isset($rs->DeletedEvent)) {
			foreach ($rs->DeletedEvent as $entry) {
				// do nothing
			}
		}

		if (isset($rs->CopiedEvent)) {
			foreach ($rs->CopiedEvent as $entry) {
				// do nothing
			}
		}

		if (isset($rs->MovedEvent)) {
			foreach ($rs->MovedEvent as $entry) {
				// do nothing
			}
		}

		if (isset($rs->StatusEvent)) {
			foreach ($rs->StatusEvent as $entry) {
				$token = $entry->Watermark;
			}
		}

		// return response
		return (object) ['Id' => $id, 'Token' => $token, 'State' => $state];

	}

}
