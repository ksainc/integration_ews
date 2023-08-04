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
use OCP\IConfig;
use Psr\Log\LoggerInterface;
use OCA\DAV\CardDAV\CardDavBackend;
use OCA\DAV\CalDAV\CalDavBackend;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Db\Action;
use OCA\EWS\Db\ActionMapper;
use OCA\EWS\Service\CoreService;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\ContactsService;
use OCA\EWS\Service\EventsService;
use OCA\EWS\Service\Local\LocalContactsService;
use OCA\EWS\Service\Local\LocalEventsService;
use OCA\EWS\Service\Remote\RemoteContactsService;
use OCA\EWS\Service\Remote\RemoteEventsService;
use OCA\EWS\Service\Remote\RemoteCommonService;
use OCA\EWS\Tasks\HarmonizationLauncher;

class HarmonizationService {

	private const EXECUSIONTIMEOUT = 60;

	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var IConfig
	 */
	private $config;
	/**
	 * @var INotificationManager
	 */
	private $notificationManager;
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
	 * @var RemoteContactsService
	 */
	private $RemoteContactsService;
	/**
	 * @var RemoteEventsService
	 */
	private $RemoteEventsService;
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
								IConfig $config,
								ActionMapper $ActionManager,
								CoreService $CoreService,
								CorrelationsService $CorrelationsService,
								LocalContactsService $LocalContactsService,
								LocalEventsService $LocalEventsService,
								RemoteContactsService $RemoteContactsService,
								RemoteEventsService $RemoteEventsService,
								RemoteCommonService $RemoteCommonService,
								ContactsService $ContactsService,
								EventsService $EventsService,
								CardDavBackend $LocalContactsStore,
								CalDavBackend $LocalEventsStore) {
		$this->logger = $logger;
		$this->config = $config;
		$this->CoreService = $CoreService;
		$this->CorrelationsService = $CorrelationsService;
		$this->LocalContactsService = $LocalContactsService;
		$this->LocalEventsService = $LocalEventsService;
		$this->RemoteContactsService = $RemoteContactsService;
		$this->RemoteEventsService = $RemoteEventsService;
		$this->RemoteCommonService = $RemoteCommonService;
		$this->ContactsService = $ContactsService;
		$this->EventsService = $EventsService;
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

		// retrieve harmonization status
		$status = $this->getStatus($uid);
		// evaluate, if harmonization is executing
		if ($status->State === 1) {
			if (is_numeric($status->Started) && (time() - intval($status->Started)) < self::EXECUSIONTIMEOUT) {
				return;
			}
		}
		unset($status);

		// update harmonization status, state and start time
		$this->setStatus($uid, 1, time(), null);
		
		try {
			// retrieve preferences
			$settings = $this->CoreService->fetchPreferences($uid);
			$settings = new \OCA\EWS\Objects\SettingsObject($settings);
			// create remote store client
			$RemoteStore = $this->CoreService->createClient($uid);
			// contacts harmonization
			if (($mode === 'S' && $settings->ContactsFrequency > 0) ||
				($mode === 'M' && $settings->ContactsFrequency > -1)) {
				$this->ContactsService->RemoteStore = $RemoteStore;;
				$this->ContactsService->Settings = $settings;
				// execute contacts harmonization loop
				do {
					// harmonize contacts collections
					$statistics = $this->ContactsService->performHarmonization();
					// evaluate if anything was done and publish notice if needed
					if ($statistics->total() > 0) {
						$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
					}
				} while ($statistics->total() > 0);
			}
			// events harmonization
			if (($mode === 'S' && $settings->EventsFrequency > 0) ||
				($mode === 'M' && $settings->EventsFrequency > -1)) {
				$this->EventsService->RemoteStore = $RemoteStore;
				$this->EventsService->Settings = $settings;
				// execute events harmonization loop
				do {
					// harmonize events collections
					$statistics = $this->EventsService->performHarmonization();
					// evaluate if anything was done and publish notice if needed
					if ($statistics->total() > 0) {
						$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
					}
				} while ($statistics->total() > 0);
			}
		} catch (Exception $e) {
			
			throw new Exception($e, 1);
			
		}
		// destroy remote store client
		$this->CoreService->destroyClient($RemoteStore);
		// update harmonization status, state and end time
		$this->setStatus($uid, 0, null, time());

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
		$status = $this->getStatus($uid);
		$ttl = $status->Started;

		try {
			// retrieve preferences
			$settings = $this->CoreService->fetchPreferences($uid);
			$settings = new \OCA\EWS\Objects\SettingsObject($settings);
			// create remote store client
			$RemoteStore = $this->CoreService->createClient($uid);
			// contacts harmonization
			if ($settings->ContactsFrequency > -1) {
				$this->ContactsService->RemoteStore = $RemoteStore;
				$this->ContactsService->Settings = $settings;
				// harmonize contact collections
				$result = $this->ContactsService->performActions($ttl);
				// evaluate if anything was done and publish notice if needed
				if ($result->total() > 0) {
					$this->CoreService->publishNotice($uid,'contacts_harmonized', (array)$statistics);
				}
			}
			// events harmonization
			if ($settings->EventsFrequency > -1) {
				$this->EventsService->RemoteStore = $RemoteStore;
				$this->EventsService->Settings = $settings;
				// harmonize event collections
				$result = $this->EventsService->performActions($ttl);
				// evaluate if anything was done and publish notice if needed
				if ($result->total() > 0) {
					$this->CoreService->publishNotice($uid,'events_harmonized', (array)$statistics);
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
					if (!($ci instanceof \OCA\EWS\Db\Correlation) || $ci->getrstate() != $ostate) {
						// construct action entry
						$a = new Action();
						$a->setuid($uid);
						$a->settype($otype);
						$a->setaction('C');
						$a->setorigin('R');
						$a->setrcid($cid);
						$a->setroid($oid);
						$a->setrstate($ostate);
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
					if (!($ci instanceof \OCA\EWS\Db\Correlation) || $ci->getrstate() != $ostate) {
						// construct action entry
						$a = new Action();
						$a->setuid($uid);
						$a->settype($otype);
						$a->setaction('U');
						$a->setorigin('R');
						$a->setrcid($cid);
						$a->setroid($oid);
						$a->setrstate($ostate);
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
						$a->setrstate($ostate);
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
	
	/**
	 * Gets harmonization mode
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization mode (default P - passive)
	 */
	public function getMode(): string {

		// retrieve harmonization mode
		$mode = $this->config->getAppValue(Application::APP_ID, 'harmonization_mode');
		
		// return thread id
		if (!empty($mode)) {
			return $mode;
		}
		else {
			return 'P';
		}

	}

	/**
	 * Sets harmonization mode
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $mode		harmonization mode (A - Active / P - Passive)
	 * 
	 * @return void
	 */
	public function setMode(string $mode): void {
		
		// set harmonization mode
		$this->config->setAppValue(Application::APP_ID, 'harmonization_mode', $mode);

	}

	/**
	 * Gets harmonization status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return object
	 */
	public function getStatus(string $uid): object {

		// construct status object
		$hs = (object) ['State' => null, 'Started' => null, 'Ended' => null];
		// retrieve status values
		$hs->State = (int) $this->config->getUserValue($uid, Application::APP_ID, 'account_harmonization_state');
		$hs->Start = (int) $this->config->getUserValue($uid, Application::APP_ID, 'account_harmonization_start');
		$hs->End = (int) $this->config->getUserValue($uid, Application::APP_ID, 'account_harmonization_end');
		// return status object
		return $hs;

	}

	/**
	 * Sets harmonization status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param int $state		harmonization state (1 - running, 0 - not running)
	 * @param int $start		harmonization start epoch time stamp
	 * @param int $end			harmonization end epoch time stamp
	 * 
	 * @return void
	 */
	public function setStatus(string $uid, int $state, ?int $start, ?int $end): void {
		
		// update harmonization values
		$this->config->setUserValue($uid, Application::APP_ID, 'account_harmonization_state', $state);
		if (isset($start)) {
			$this->config->setUserValue($uid, Application::APP_ID, 'account_harmonization_start', $start);
		}
		if (isset($end)) {
			$this->config->setUserValue($uid, Application::APP_ID, 'account_harmonization_end', $end);
		}

	}

	/**
	 * Gets harmonization thread run duration interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization thread run duration interval (default 3600 seconds)
	 */
	public function getThreadDuration(): int {

		// retrieve value
		$interval = $this->config->getAppValue(Application::APP_ID, 'harmonization_thread_duration');
		
		// return value or default
		if (is_numeric($interval)) {
			return intval($interval);
		}
		else {
			return 3600;
		}

	}

	/**
	 * Sets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $interval		harmonization thread pause interval in seconds
	 * 
	 * @return void
	 */
	public function setThreadDuration(int $interval): void {
		
		// set value
		$this->config->setAppValue(Application::APP_ID, 'harmonization_thread_duration', $mode);

	}

	/**
	 * Gets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization thread pause interval (default 5 seconds)
	 */
	public function getThreadPause(): int {

		// retrieve value
		$interval = $this->config->getAppValue(Application::APP_ID, 'harmonization_thread_pause');
		
		// return value or default
		if (is_numeric($interval)) {
			return intval($interval);
		}
		else {
			return 5;
		}

	}

	/**
	 * Sets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $interval		harmonization thread pause interval in seconds
	 * 
	 * @return void
	 */
	public function setThreadPause(int $interval): void {
		
		// set value
		$this->config->setAppValue(Application::APP_ID, 'harmonization_thread_pause', $mode);

	}

}
