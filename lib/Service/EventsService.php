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

use Datetime;
use DateTimeZone;
use Exception;
use Throwable;
use Psr\Log\LoggerInterface;
use OCA\DAV\CalDav\CalDavBackend;
use OCP\Files\IRootFolder;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Db\ActionMapper;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\Local\LocalEventsService;
use OCA\EWS\Service\Remote\RemoteEventsService;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Objects\EventObject;
use OCA\EWS\Objects\HarmonizationStatisticsObject;

class EventsService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var CorrelationsService
	 */
	private $CorrelationsService;
	/**
	 * @var LocalEventsService
	 */
	private $LocalEventsService;
	/**
	 * @var RemoteEventsService
	 */
	private $RemoteEventsService;
	/**
	 * @var CalDavBackend
	 */
	private $LocalStore;
	/**
	 * @var IRootFolder
	 */
	private $LocalFileStore;
	/**
	 * @var EWSClient
	 */
	public $RemoteStore;
	/**
	 * @var Object
	 */
	private $Configuration;
	/**
	 * @var array
	 */
	private $RemoteUUIDs;

	public function __construct (string $appName,
								LoggerInterface $logger,
								ActionMapper $ActionMapper,
								CorrelationsService $CorrelationsService,
								LocalEventsService $LocalEventsService,
								RemoteEventsService $RemoteEventsService,
								CalDavBackend $LocalStore,
								IRootFolder $LocalFileStore) {
		$this->logger = $logger;
		$this->ActionsManager = $ActionMapper;
		$this->CorrelationsService = $CorrelationsService;
		$this->LocalEventsService = $LocalEventsService;
		$this->RemoteEventsService = $RemoteEventsService;
		$this->LocalStore = $LocalStore;
		$this->LocalFileStore = $LocalFileStore;
	}

	/**
	 * Perform harmonization for all events collection correlations
	 * 
	 * @since Release 1.0.0
	 *
	 * @return HarmonizationStatisticsObject
	 */
	public function performHarmonization($correlation, $configuration) : object {
		$this->Configuration = $configuration;
		// assign data stores
		$this->LocalEventsService->DataStore = $this->LocalStore;
		$this->LocalEventsService->FileStore = $this->LocalFileStore->getUserFolder($this->Configuration->UserId);
		$this->RemoteEventsService->DataStore = $this->RemoteStore;
		// assign timezones
		$this->LocalEventsService->UserTimeZone = $this->Configuration->UserTimeZone;
		$this->RemoteEventsService->UserTimeZone = $this->Configuration->UserTimeZone;
		// construct statistics object
		$statistics = new HarmonizationStatisticsObject();
		
		// construct UUID's place holder
		$this->RemoteUUIDs = null;
		// set local and remote collection id's
		$caid = (string) $correlation->getid();
		$lcid = $correlation->getloid();
		$rcid = $correlation->getroid();
		// delete and skip collection correlation if remote id or local id is missing
		if (empty($lcid) || empty($rcid)){
			$this->CorrelationsService->deleteByAffiliationId($this->Configuration->UserId, $caid);
			$this->CorrelationsService->delete($correlation);
			$this->logger->debug('EWS - Deleted Events collection correlation for ' . $this->Configuration->UserId . ' due to missing Remote ID or Local ID');
			return $statistics;
		}
		// delete and skip collection correlation if local collection is missing
		$lcollection = $this->LocalEventsService->fetchCollection($lcid);
		if (!isset($lcollection) || ($lcollection->Id != $lcid)) {
			$this->CorrelationsService->deleteByAffiliationId($this->Configuration->UserId, $caid);
			$this->CorrelationsService->delete($correlation);
			$this->logger->debug('EWS - Deleted Events collection correlation for ' . $this->Configuration->UserId . ' due to missing Local Collection');
			return $statistics;
		}
		// delete and skip collection correlation if remote collection is missing
		$rcollection = $this->RemoteEventsService->fetchCollection($rcid);
		if (!isset($rcollection) || ($rcollection->Id != $rcid)) {
			$this->CorrelationsService->deleteByAffiliationId($this->Configuration->UserId, $caid);
			$this->CorrelationsService->delete($correlation);
			$this->logger->debug('EWS - Deleted Events collection correlation for ' . $this->Configuration->UserId . ' due to missing Remote Collection');
			return $statistics;
		}
		// retrieve list of local changed objects
		$lCollectionChanges = $this->LocalEventsService->fetchCollectionChanges($correlation->getloid(), (string) $correlation->getlostate());
		// process local created objects
		foreach ($lCollectionChanges['added'] as $iid) {
			// process create
			$as = $this->harmonizeLocalAltered(
				$this->Configuration->UserId, 
				$lcid, 
				$iid, 
				$rcid, 
				$caid
			);
			// increment statistics
			switch ($as) {
				case 'RC':
					$statistics->RemoteCreated += 1;
					break;
				case 'RU':
					$statistics->RemoteUpdated += 1;
					break;
				case 'LU':
					$statistics->LocalUpdated += 1;
					break;
			}
		}
		// process local modified items
		foreach ($lCollectionChanges['modified'] as $iid) {
			// process create
			$as = $this->harmonizeLocalAltered(
				$this->Configuration->UserId, 
				$lcid, 
				$iid, 
				$rcid, 
				$caid
			);
			// increment statistics
			switch ($as) {
				case 'RC':
					$statistics->RemoteCreated += 1;
					break;
				case 'RU':
					$statistics->RemoteUpdated += 1;
					break;
				case 'LU':
					$statistics->LocalUpdated += 1;
					break;
			}
		}
		// process local deleted items
		foreach ($lCollectionChanges['deleted'] as $iid) {
			// process delete
			$as = $this->harmonizeLocalDelete(
				$this->Configuration->UserId, 
				$lcid, 
				$iid
			);
			if ($as == 'RD') {
				// assign status
				$statistics->RemoteDeleted += 1;
			}
		}
		// Make sure to store this for the next sync.
		$correlation->setlostate($lCollectionChanges['syncToken']);
		$this->CorrelationsService->update($correlation);

		// retrieve list of remote changed object
		$rCollectionChanges = $this->RemoteEventsService->fetchCollectionChanges($correlation->getroid(), (string) $correlation->getrostate());
		// process remote created objects
		foreach ($rCollectionChanges->Create as $changed) {
			// process create
			$as = $this->harmonizeRemoteAltered(
				$this->Configuration->UserId, 
				$rcid, 
				$changed->CalendarItem->ItemId->Id, 
				$lcid, 
				$caid
			);
			// increment statistics
			switch ($as) {
				case 'LC':
					$statistics->LocalCreated += 1;
					break;
				case 'LU':
					$statistics->LocalUpdated += 1;
					break;
				case 'RU':
					$statistics->RemoteUpdated += 1;
					break;
			}
		}
		// process remote modified objects
		foreach ($rCollectionChanges->Update as $changed) {
			// process update
			$as = $this->harmonizeRemoteAltered(
				$this->Configuration->UserId, 
				$rcid, 
				$changed->CalendarItem->ItemId->Id, 
				$lcid, 
				$caid
			);
			// increment statistics
			switch ($as) {
				case 'LC':
					$statistics->LocalCreated += 1;
					break;
				case 'LU':
					$statistics->LocalUpdated += 1;
					break;
				case 'RU':
					$statistics->RemoteUpdated += 1;
					break;
			}
		}
		// process remote deleted objects
		foreach ($rCollectionChanges->Delete as $changed) {
			// process delete
			$as = $this->harmonizeRemoteDelete(
				$this->Configuration->UserId, 
				$rcid, 
				$changed->ItemId->Id
			);
			if ($as == 'LD') {
				// increment statistics
				$statistics->LocalDeleted += 1;
			}
		}
		// Make sure to store this for the next sync.
		$correlation->setrostate($rCollectionChanges->SyncToken);
		$this->CorrelationsService->update($correlation);
		// destroy UUID's place holder
		unset($this->RemoteUUIDs);

		// return statistics
		return $statistics;

	}
	
	/**
	 * Perform actions for contacts events
	 * 
	 * @since Release 1.0.0
	 *
	 * @return void
	 */
	public function performActions($syncedOn, $configuration) : object {
		$this->Configuration = $configuration;
		// assign data stores
		$this->LocalEventsService->DataStore = $this->LocalStore;
		$this->RemoteEventsService->DataStore = $this->RemoteStore;
		// construct statistics object
		$statistics = new \OCA\EWS\Objects\HarmonizationStatisticsObject();
		// retrieve list of actions
		$actions = $this->ActionsManager->findByType($this->Configuration->UserId, 'EO');
		// iterate through correlation items
		foreach ($actions as $action) {
			// evaluate action, if action was created before last harmonization ignore it and delete it.
			// harmonization already processed the changes
			if ($syncedOn !== '' && intval($syncedOn) > strtotime($action->getcreatedon())) {
				$this->ActionsManager->delete($action);
				continue;
			}
			// evaluate, action origin
			if ($action->getorigin() == "L") {
				// retrieve collection corrollation
				$cc = $this->CorrelationsService->findByLocalId($this->Configuration->UserId, 'EC', $action->getlcid());
				// evaluate corrollation, if corrollation exists for the local collection create action
				if ($cc instanceof \OCA\EWS\Db\Correlation) {
					// process based on action
					switch ($action->getaction()) {
						case 'C':
						case 'U':
							$as = $this->harmonizeLocalAltered(
								$this->Configuration->UserId,
								$cc->getloid(),
								$action->getloid(),
								$cc->getroid(),
								$cc->getid()
							);
							// increment statistics
							switch ($as) {
								case 'RC':
									$statistics->RemoteCreated += 1;
									break;
								case 'RU':
									$statistics->RemoteUpdated += 1;
									break;
								case 'LU':
									$statistics->LocalUpdate += 1;
									break;
							}
							break;
						case 'D':
							$as = $this->harmonizeLocalDelete(
								$this->Configuration->UserId,
								$cc->getloid(),
								$action->getloid()
							);
							if ($as == 'RD') {
								// assign status
								$statistics->RemoteDeleted += 1;
							}
							break;
					}
				}
			}
			elseif ($action->getorigin() == "R") {
				// retrieve collection corrollation
				$cc = $this->CorrelationsService->findByRemoteId($this->Configuration->UserId, 'EC', $action->getrcid());
				// evaluate corrollation, if corrollation exists for the remote collection create action
				if ($cc instanceof \OCA\EWS\Db\Correlation) {
					// process based on action
					switch ($action->getaction()) {
						case 'C':
						case 'U':
							$as = $this->harmonizeRemoteAltered(
								$this->Configuration->UserId,
								$cc->getroid(),
								$action->getroid(),
								$cc->getloid(),
								$cc->getid()
							);
							// increment statistics
							switch ($as) {
								case 'LC':
									$statistics->LocalCreated += 1;
									break;
								case 'LU':
									$statistics->LocalUpdated += 1;
									break;
								case 'RU':
									$statistics->RemoteUpdate += 1;
									break;
							}
							break;
						case 'D':
							$as = $this->harmonizeRemoteDelete(
								$this->Configuration->UserId,
								$cc->getroid(),
								$action->getroid()
							);
							if ($as == 'LD') {
								// increment statistics
								$statistics->LocalDeleted += 1;
							}
							break;
					}
				}
			}
			// destroy action
			$this->ActionsManager->delete($action);
		}

		// return statistics
		return $statistics;

	}

	/**
	 * Perform harmonization for locally created object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $lcid	local collection id
	 * @param string $loid	local object id
	 * @param string $rcid	remote collection id
	 * @param string $caid	correlation affiliation id
	 *
	 * @return string what action was performed
	 */
	function harmonizeLocalCreated ($uid, $lcid, $loid, $rcid, $caid): string {
		
		// create harmonize status place holder
		$status = 'NA'; // no actions
		// if local object id contains 'delete' stop processing object
		// workaround for trashed object showing as created
		if (str_contains($loid, 'deleted')) {
			// return status of action
			return $status;
		}
		// create/reset local object place holder
		$lo = null;
		// create/reset remote object place holder
		$ro = null;
		// retrieve local Events object
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $loid);
		// evaluate, if local event object was returned
		if (!($lo instanceof \OCA\EWS\Objects\EventObject)) {
			// return status of action
			return $status;
		}
		// try to retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByLocalId($uid, 'EO', $loid, $lcid);
		// if correlation exists
		// compare local state to correlation state and stop processing if they match to prevent sync loop
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getlostate() == $lo->State) {
			// return status of action
			return $status;
		}
		// if correlation exists, try to retrieve remote object
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getroid()) {
			// retrieve remote event object
			$ro = $this->RemoteEventsService->fetchCollectionItem($ci->getroid());
		}
		// if remote object retrieve failed, try to retrieve remote object by UUID
		if (!isset($ro) && !empty($lo->UUID)) {
			// test
			$ro = $this->RemoteEventsService->fetchCollectionItemByUUID($rcid, $lo->UUID);
			// retrieve list of remote ids and uids
			if (!isset($this->RemoteUUIDs)) {
				$this->RemoteUUIDs = $this->RemoteEventsService->fetchCollectionItemsUUID($rcid);
			}
			// search for uuid
			$k = array_search($lo->UUID, array_column($this->RemoteUUIDs, 'UUID'));
			if ($k !== false) {
				// retrieve remote event object
				$ro = $this->RemoteEventsService->fetchCollectionItem($this->RemoteUUIDs[$k]['ID']);
			}
		}
		// update remote object if one was found
		// create remote object if none was found
		if (isset($ro)) {
			// update remote object if
			// local wins mode selected
			// chronology wins mode selected and local object is newer
			if ($this->Configuration->EventsPrevalence == 'L' || 
			($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
				// delete all previous attachment(s) in remote store
				// work around for missing update command in ews
				$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
				// update remote object
				$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
				// assign status
				$status = 'RU'; // Rocal Update
			}
			// update local object if
			// remote wins mode selected
			// chronology wins mode selected and remote object is newer
			if ($this->Configuration->EventsPrevalence == 'R' || 
			($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
				// update local object
				$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
				// assign status
				$status = 'LU'; // Local Update
			}
		} else {
			// create remote object
			$ro = $this->RemoteEventsService->createCollectionItem($rcid, $lo);
			// assign status
			$status = 'RC'; // Remote Create
		}
		// update object correlation if one was found
		// create object correlation if none was found
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->update($ci);
		}
		elseif (isset($lo) && isset($ro)) {
			$ci = new \OCA\EWS\Db\Correlation();
			$ci->settype('EO'); // Correlation Type
			$ci->setuid($uid); // User ID
			$ci->setaid($caid); //Affiliation ID
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->create($ci);
		}
	}

	/**
	 * Perform harmonization for locally altered object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $lcid	local collection id
	 * @param string $loid	local object id
	 * @param string $rcid	remote collection id
	 * @param string $caid	correlation affiliation id
	 *
	 * @return string what action was performed
	 */
	function harmonizeLocalAltered ($uid, $lcid, $loid, $rcid, $caid): string {
		
		// create harmonize status place holder
		$status = 'NA'; // no actions
		// create/reset local object place holder
		$lo = null;
		// create/reset remote object place holder
		$ro = null;
		// retrieve local events object
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $loid);
		// evaluate, if local event object was returned
		if (!($lo instanceof \OCA\EWS\Objects\EventObject)) {
			// return status of action
			return $status;
		}
		// try to retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByLocalId($uid, 'EO', $loid, $lcid);
		// if correlation exists
		// compare local state to correlation state and stop processing if they match to prevent sync loop
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getlostate() == $lo->State) {
			// return status of action
			return $status;
		}
		// if correlation exists, try to retrieve remote object
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			!empty($ci->getroid())) {
			// retrieve remote event object
			$ro = $this->RemoteEventsService->fetchCollectionItem($ci->getroid());
		}
		// if remote object retrieve failed, try to retrieve remote object by UUID
		if (!isset($ro) && !empty($lo->UUID)) {
			// retrieve list of remote ids and uids
			if (!isset($this->RemoteUUIDs)) {
				$this->RemoteUUIDs = $this->RemoteEventsService->fetchCollectionItemsUUID($rcid);
			}
			// search for uuid
			$k = array_search($lo->UUID, array_column($this->RemoteUUIDs, 'UUID'));
			if ($k !== false) {
				// retrieve remote event object
				$ro = $this->RemoteEventsService->fetchCollectionItem($this->RemoteUUIDs[$k]['ID']);
			}
		}
		// create remote object if none was found
		// update remote object if one was found
		if (isset($ro)) {
			// if correlation DOES NOT EXIST
			// use selected mode to resolve conflict
			if (!($ci instanceof \OCA\EWS\Db\Correlation)) {
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->EventsPrevalence == 'L' || 
					($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
					// assign status
					$status = 'RU'; // Remote Update
				}
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->EventsPrevalence == 'R' || 
					($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
			}
			// if correlation EXISTS
			// compare remote object state to correlation state
			// if states DO NOT MATCH use selected mode to resolve conflict
			elseif ($ci instanceof \OCA\EWS\Db\Correlation && 
				$ro->State != $ci->getrostate()) {
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->EventsPrevalence == 'L' || 
				   ($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
					// assign status
					$status = 'RU'; // Rocal Update
				}
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->EventsPrevalence == 'R' || 
				   ($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
			}
			// if correlation EXISTS
			// compare remote object state to correlation state
			// if states DO MATCH update remote object
			elseif ($ci instanceof \OCA\EWS\Db\Correlation && 
					$ro->State == $ci->getrostate()) {
				// delete all previous attachment(s) in remote store
				// work around for missing update command in ews
				$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
				// update remote object
				$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
				// assign status
				$status = 'RU'; // Remote Update
			}
		}
		else {
			// create remote object
			$ro = $this->RemoteEventsService->createCollectionItem($rcid, $lo);
			// assign status
			$status = 'RC'; // Remote Create
		}
		// update object correlation if one was found
		// create object correlation if none was found
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->update($ci);
		}
		elseif (isset($lo) && isset($ro)) {
			$ci = new \OCA\EWS\Db\Correlation();
			$ci->settype('EO'); // Correlation Type
			$ci->setuid($uid); // User ID
			$ci->setaid($caid); //Affiliation ID
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->create($ci);
		}
		// return status of action
		return $status;

	}

	/**
	 * Perform harmonization for locally deleted object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $lcid	local collection id
	 * @param string $loid	local object id
	 *
	 * @return string what action was performed
	 */
	function harmonizeLocalDelete ($uid, $lcid, $loid): string {
		
		// retrieve correlation
		$ci = $this->CorrelationsService->findByLocalId($uid, 'EO', $loid, $lcid);
		// evaluate correlation object
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			// destroy remote object
			$rs = $this->RemoteEventsService->deleteCollectionItem($ci->getroid());
			// destroy correlation
			$this->CorrelationsService->delete($ci);
			// return status of action
			return 'RD';
		}
		else {
			// return status of action
			return 'NA';
		}

	}

	/**
	 * Perform harmonization for remotely created object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $rcid	remote collection id
	 * @param string $roid	remote object id
	 * @param string $lcid	local collection id
	 * @param string $caid	correlation affiliation id
	 *
	 * @return string what action was performed
	 */
	function harmonizeRemoteCreate ($uid, $rcid, $roid, $lcid, $caid): string {

		// create harmonize status place holder
		$status = 'NA'; // no actions
		// create/reset remote object place holder
		$ro = null;
		// create/reset local object place holder
		$lo = null;
		// retrieve remote event object
		$ro = $this->RemoteEventsService->fetchCollectionItem($roid);
		// evaluate, if remote event object was returned
		if (!($ro instanceof \OCA\EWS\Objects\EventObject)) {
			// return status of action
			return $status;
		}
		// retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByRemoteId($uid, 'EO', $roid, $rcid);
		// if correlation exists
		// compare update state to correlation state and stop processing if they match to prevent sync loop
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getrostate() == $ro->State) {
			// return status of action
			return $status;
		}
		// if correlation exists, try to retrieve local object
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getloid()) {			
			$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $ci->getloid());
		}
		// if local object retrieve failed, try to retrieve local object by UUID
		if (!isset($lo) && !empty($ro->UUID)) {
			$lo = $this->LocalEventsService->findCollectionItemByUUID($lcid, $ro->UUID);
		}
		// update local object if one was found
		// create local object if none was found
		if (isset($lo)) {
			// update local object if
			// remote wins mode selected
			// chronology wins mode selected and remote object is newer
			if ($this->Configuration->EventsPrevalence == 'R' || 
			   ($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
				// update local object
				$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
				// assign status
				$status = 'LU'; // Local Update
			}
			// update remote object if
			// local wins mode selected
			// chronology wins mode selected and local object is newer
			if ($this->Configuration->EventsPrevalence == 'L' || 
			   ($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
				// delete all previous attachment(s) in remote store
				// work around for missing update command in ews
				$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
				// update remote object
				$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
				// assign status
				$status = 'RU'; // Remote Update
			}
		}
		else {
			// create local object
			$lo = $this->LocalEventsService->createCollectionItem($lcid, $ro);
			// update remote object uuid if was missing
			if (empty($ro->UUID)) {
				$rs = $this->RemoteEventsService->updateCollectionItemUUID($rcid, $ro->ID, $lo->UUID);
				if ($rs) { $ro->State = $rs->State; }
			}
			// assign status
			$status = 'LC'; // Local Create
		}
		// update object correlation if one was found
		// create object correlation if none was found
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->update($ci);
		}
		elseif (isset($ro) && isset($lo)) {
			$ci = new \OCA\EWS\Db\Correlation();
			$ci->settype('EO'); // Correlation Type
			$ci->setuid($uid); // User ID
			$ci->setaid($caid); //Affiliation ID
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->create($ci);
		}
		// return status of action
		return $status;

	}

	/**
	 * Perform harmonization for remotely altered object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $rcid	remote collection id
	 * @param string $roid	remote object id
	 * @param string $lcid	local collection id
	 * @param string $caid	correlation affiliation id
	 *
	 * @return string what action was performed
	 */
	function harmonizeRemoteAltered ($uid, $rcid, $roid, $lcid, $caid): string {
		
		// create harmonize status place holder
		$status = 'NA'; // no acction
		// create/reset remote object place holder
		$ro = null;
		// create/reset local object place holder
		$lo = null;
		// retrieve remote event object
		$ro = $this->RemoteEventsService->fetchCollectionItem($roid);
		// evaluate, if remote event object was returned
		if (!($ro instanceof \OCA\EWS\Objects\EventObject)) {
			// return status of action
			return $status;
		}
		// retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByRemoteId($uid, 'EO', $roid, $rcid);
		// if correlation exists, compare update state to correlation state and stop processing if they match
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getrostate() == $ro->State) {
			// return status of action
			return $status;
		}
		// if correlation exists, try to retrieve local object
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getloid()) {			
			$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $ci->getloid());
		}
		// if local object retrieve failed, try to retrieve local object by UUID
		if (!isset($lo) && !empty($ro->UUID)) {
			$lo = $this->LocalEventsService->findCollectionItemByUUID($lcid, $ro->UUID);
		}
		// update local object if one was found
		// create local object if none was found
		if (isset($lo)) {
			// if correlation DOES NOT EXIST
			// use selected mode to resolve conflict
			if (!($ci instanceof \OCA\EWS\Db\Correlation)) {
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->EventsPrevalence == 'R' || 
					($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->EventsPrevalence == 'L' || 
					($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
					// assign status
					$status = 'RU'; // Remote Update
				}
			}
			// if correlation EXISTS
			// compare local object state to correlation state
			// if states DO NOT MATCH use selected mode to resolve conflict
			elseif ($ci instanceof \OCA\EWS\Db\Correlation && 
				$lo->State != $ci->getlostate()) {
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->EventsPrevalence == 'R' || 
				   ($this->Configuration->EventsPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->EventsPrevalence == 'L' || 
				   ($this->Configuration->EventsPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $lo);
					// assign status
					$status = 'RU'; // Remote Update
				}
			}
			// if correlation EXISTS
			// compare local object state to correlation state
			// if states DO MATCH update local object
			elseif ($ci instanceof \OCA\EWS\Db\Correlation && 
					$lo->State == $ci->getlostate()) {
				// update local object
				$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $ro);
				// assign status
				$status = 'LU'; // Local Update
			}
		}
		else {
			// create local object
			$lo = $this->LocalEventsService->createCollectionItem($lcid, $ro);
			// update remote object uuid if was missing
			if (empty($ro->UUID)) {
				$rs = $this->RemoteEventsService->updateCollectionItemUUID($rcid, $ro->ID, $lo->UUID);
				if ($rs) { $ro->State = $rs->State; }
			}
			// assign status
			$status = 'LC'; // Local Create
		}
		// update object correlation if one was found
		// create object correlation if none was found
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->update($ci);
		}
		elseif (isset($ro) && isset($lo)) {
			$ci = new \OCA\EWS\Db\Correlation();
			$ci->settype('EO'); // Correlation Type
			$ci->setuid($uid); // User ID
			$ci->setaid($caid); //Affiliation ID
			$ci->setloid($lo->ID); // Local ID
			$ci->setlostate($lo->State); // Local State
			$ci->setlcid($lcid); // Local Parent ID
			$ci->setroid($ro->ID); // Remote ID
			$ci->setrostate($ro->State); // Remote State
			$ci->setrcid($rcid); // Remote Parent ID
			$this->CorrelationsService->create($ci);
		}
		// return status of action
		return $status;
	}

	/**
	 * Perform harmonization for remotely deleted object
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $rcid	local collection id
	 * @param string $roid	local object id
	 *
	 * @return string what action was performed
	 */
	function harmonizeRemoteDelete ($uid, $rcid, $roid): string {

		// retrieve correlation
		$ci = $this->CorrelationsService->findByRemoteId($uid, 'EO', $roid, $rcid);
		// evaluate correlation object
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			// destroy local object
			$rs = $this->LocalEventsService->deleteCollectionItem($ci->getlcid(), $ci->getloid());
			// destroy correlation
			$this->CorrelationsService->delete($ci);
			// return status of action
			return 'LD';
		}
		else {
			// return status of action
			return 'NA';
		}

	}

	/**
	 * Creates and Deletes Test Data
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $action	action to perform (C - create / D - delete)
	 *
	 * @return void
	 */
	public function performTest($action, $configuration) : void {
		// assign data stores
		$this->LocalEventsService->DataStore = $this->LocalStore;
		$this->LocalEventsService->FileStore = $this->LocalFileStore->getUserFolder($configuration->UserId);
		$this->RemoteEventsService->DataStore = $this->RemoteStore;
		// assign timezones
		$this->LocalEventsService->UserTimeZone = $configuration->SystemTimeZone;
		$this->RemoteEventsService->UserTimeZone = $configuration->SystemTimeZone;

		/*
		*	Test Basic Collection Functions
		*/
		// retrieve local event collections
		$lc = $this->LocalEventsService->listCollections($configuration->UserId);
		foreach ($lc as $entry) {
			if ($entry['name'] == 'EWS Calendar') {
				$lcid = $entry['id'];
				break;
			}
		}
		// retrieve remote event collections
		$rc = $this->RemoteEventsService->listCollections();
		foreach ($rc as $entry) {
			if ($entry['name'] == 'NC Calendar') {
				$rcid = $entry['id'];
				break;
			}
		}

		// if action delete, delete the collections stop
		if ($action == 'D') {
			if (isset($lcid)) {
				$this->LocalEventsService->deleteCollection($lcid, true);
			}
			if (isset($rcid)) {
				$this->RemoteEventsService->deleteCollection($rcid);
			}
			return;
		}

		// create local collection
		if (!isset($lcid)) {
			$lco = $this->LocalEventsService->createCollection($configuration->UserId, 'ews-test', 'EWS Calendar', true);
			$lcid = $lco->Id;
		}
		// create remote collection
		if (!isset($rcid)) {
			$rco = $this->RemoteEventsService->createCollection('msgfolderroot', 'NC Calendar', true);
			$rcid = $rco->Id;
		}
		// retrieve correlation for remote and local collections
		$ci = $this->CorrelationsService->find($configuration->UserId, $lcid, $rcid);
		// create correlation if none was found
		if (!isset($ci)) {
			$ci = new \OCA\EWS\Db\Correlation();
			$ci->settype('EC'); // Correlation Type
			$ci->setuid($configuration->UserId); // User ID
			$ci->setloid($lcid); // Local ID
			$ci->setroid($rcid); // Remote ID
			$this->CorrelationsService->create($ci);
		}
		// retrieve local collection properties
		$lco = $this->LocalEventsService->fetchCollection($lcid);
		// retrieve remote collection properties
		$rco = $this->RemoteEventsService->fetchCollection($rcid);
		// retrieve local collection changes
		$lcc = $this->LocalEventsService->fetchCollectionChanges($lcid, '');
		// retrieve remote collection changes
		$rcc = $this->RemoteEventsService->fetchCollectionChanges($rcid, '');

		/*
		*	Test Basic Collection Item Properties
		*/
		// construct event object with basic properties
		$eo = new EventObject();
		$eo->Origin = 'L';
		$eo->Notes = 'Don\'t forget to bring a present';
		$eo->StartsOn = (new DateTime('NOW')); 
		$eo->StartsTZ = $configuration->SystemTimeZone;
		$eo->StartsOn->setTimezone($configuration->SystemTimeZone);
		$eo->StartsOn->modify('next saturday')->setTime(20, 0, 0, 0); // set event start next saturday at 10:00
		$eo->EndsOn = (clone $eo->StartsOn)->modify('+2 hour'); // set event end on same day one hour later
		$eo->EndsTZ = (clone $eo->StartsTZ);
		$eo->Location = 'Moes Pub';
		$eo->Availability = 'Busy';
		$eo->Priority = '1';
		$eo->Sensitivity = '1';
		// generate new uuid for local
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'NC Simpson\'s Birthday Dinner';
		// create local event
		$lo = $this->LocalEventsService->createCollectionItem($lcid, $eo);
		// retrieve local event
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $lo->ID);
		// update local event
		$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $eo);
		// retrieve local event by uuid
		$lo = $this->LocalEventsService->findCollectionItemByUUID($lcid, $eo->UUID);
		// generate new uuid for remote
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'EWS Simpson\'s Birthday Dinner';
		// create remote event
		$ro = $this->RemoteEventsService->createCollectionItem($rcid, $eo);
		// retrieve remote event
		$ro = $this->RemoteEventsService->fetchCollectionItem($ro->ID);
		// update remote event
		$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $eo);
		// update remote event uuid
		$ro = $this->RemoteEventsService->updateCollectionItemUUID($rcid, $ro->ID, $ro->UUID);
		// retrieve remote event by uuid
		$ro = $this->RemoteEventsService->fetchCollectionItemByUUID($rcid, $eo->UUID);

		/*
		*	Test Collection Item Tags / Attendees / Notifications / Attachments
		*/
		$eo = new EventObject();
		$eo->Origin = 'L';
		$eo->Notes = 'Bart done it again';
		$eo->StartsOn = (new DateTime('now', $configuration->SystemTimeZone))->modify('next sunday')->setTime(10, 0, 0, 0);
		$eo->StartsTZ = $configuration->SystemTimeZone;
		$eo->EndsOn = (clone $eo->StartsOn)->modify('+1 hour');
		$eo->EndsTZ = (clone $eo->StartsTZ);
		$eo->Availability = 'Busy';
		$eo->Priority = '1';
		$eo->Sensitivity = '1';
		$eo->addTag('First Tag');
		$eo->addTag('Second Tag');
		$eo->addAttendee('homer@simpsons.fake', 'Homer Simpson', 'R', 'T');
		$eo->addAttendee('marge@simpsons.fake', 'Marge Simpson', 'R', 'A');
		$eo->addAttendee('bart@simpsons.fake', 'Bart Simpson', 'O', 'D');
		$di = new \DateInterval('PT1H');
		$di->invert = 1;
		$eo->addNotification('D', 'R', $di);
		$eo->addAttachment(
			'D',
			null,
			'test.txt',
			'text/plain',
			'B',
			null,
			'This is a text string inside the test file.'
		);
		// generate new uuid for local
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'NC Simpson Meeting';
		// create local event
		$lo = $this->LocalEventsService->createCollectionItem($lcid, $eo);
		// retrieve local event
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $lo->ID);
		// update local event
		$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $eo);
		// generate new uuid for remote
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'EWS Simpson Meeting';
		// create remote event
		$ro = $this->RemoteEventsService->createCollectionItem($rcid, $eo);
		// retrieve remote event
		$ro = $this->RemoteEventsService->fetchCollectionItem($ro->ID);
		// update remote event
		// delete all previous attachment(s) in remote store
		// work around for missing update command in ews
		$this->RemoteEventsService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
		$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $eo);
		
		/*
		*	Test Collection Item Occurance Daily
		*/
		$eo = new EventObject();
		$eo->Origin = 'L';
		$eo->Notes = 'Every other day for 4 iterations';
		$eo->StartsOn = (new DateTime('now', $configuration->SystemTimeZone))->modify('next monday')->setTime(10, 0, 0, 0);
		$eo->StartsTZ = $configuration->SystemTimeZone;
		$eo->EndsOn = (clone $eo->StartsOn)->modify('+1 hour');
		$eo->EndsTZ = (clone $eo->StartsTZ);
		$eo->Availability = 'Busy';
		$eo->Priority = '1';
		$eo->Sensitivity = '1';
		$eo->Occurrence->Pattern = 'A'; // Absolute
		$eo->Occurrence->Precision = 'D'; // Daily
		$eo->Occurrence->Interval = 2; // Every Other Day
		$eo->Occurrence->Iterations = 4; // Only 4 times
		// generate new uuid for local
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'NC Daily Occurance';
		// create local event
		$lo = $this->LocalEventsService->createCollectionItem($lcid, $eo);
		// retrieve local event
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $lo->ID);
		// update local event
		$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $eo);
		// generate new uuid for remote
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'EWS Daily Occurance';
		// create remote event
		$ro = $this->RemoteEventsService->createCollectionItem($rcid, $eo);
		// retrieve remote event
		$ro = $this->RemoteEventsService->fetchCollectionItem($ro->ID);
		// update remote event
		$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $eo);

		/*
		*	Test Collection Item Occurance Weekly
		*/
		$eo = new EventObject();
		$eo->Origin = 'L';
		$eo->Notes = 'Every other week on Monday, Wednesday and Friday until 4 Weeks from start';
		$eo->StartsOn = (new DateTime('now', $configuration->SystemTimeZone))->modify('next monday')->setTime(12, 0, 0, 0);
		$eo->StartsTZ = $configuration->SystemTimeZone;
		$eo->EndsOn = (clone $eo->StartsOn)->modify('+1 hour');
		$eo->EndsTZ = (clone $eo->StartsTZ);
		$eo->Availability = 'Busy';
		$eo->Priority = '1';
		$eo->Sensitivity = '1';
		$eo->Occurrence->Pattern = 'A'; // Absolute
		$eo->Occurrence->Precision = 'W'; // Daily
		$eo->Occurrence->Interval = 2; // Every Other Week
		$eo->Occurrence->Concludes = (clone $eo->StartsOn)->modify('+4 Weeks');
		$eo->Occurrence->OnDayOfWeek = array(1, 3, 5);
		// generate new uuid for local
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'NC Weekly Occurance';
		// create local event
		$lo = $this->LocalEventsService->createCollectionItem($lcid, $eo);
		// retrieve local event
		$lo = $this->LocalEventsService->fetchCollectionItem($lcid, $lo->ID);
		// update local event
		$lo = $this->LocalEventsService->updateCollectionItem($lcid, $lo->ID, $eo);
		// generate new uuid for remote
		$eo->UUID = \OCA\EWS\Utile\UUID::v4();
		$eo->Label = 'EWS Weekly Occurance';
		// create remote event
		$ro = $this->RemoteEventsService->createCollectionItem($rcid, $eo);
		// retrieve remote event
		$ro = $this->RemoteEventsService->fetchCollectionItem($ro->ID);
		// update remote event
		$ro = $this->RemoteEventsService->updateCollectionItem($rcid, $ro->ID, $eo);

	}
	
}
