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
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\Local\LocalTasksService;
use OCA\EWS\Service\Remote\RemoteTasksService;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Objects\TaskObject;
use OCA\EWS\Objects\HarmonizationStatisticsObject;

class TasksService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var CorrelationsService
	 */
	private $CorrelationsService;
	/**
	 * @var LocalTasksService
	 */
	private $LocalTasksService;
	/**
	 * @var RemoteTasksService
	 */
	private $RemoteTasksService;
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
								CorrelationsService $CorrelationsService,
								LocalTasksService $LocalTasksService,
								CalDavBackend $LocalStore,
								IRootFolder $LocalFileStore) {
		$this->logger = $logger;
		$this->CorrelationsService = $CorrelationsService;
		$this->LocalTasksService = $LocalTasksService;
		$this->LocalStore = $LocalStore;
		$this->LocalFileStore = $LocalFileStore;
	}

	public function configure($configuration, EWSClient $RemoteStore) : void {
		
		// assign configuration
		$this->Configuration = $configuration;
		// assign remote data store
		$this->RemoteStore = $RemoteStore;
		// construct remote service
		switch ($RemoteStore->getVersion()) {
			case $RemoteStore::SERVICE_VERSION_2007:
			case $RemoteStore::SERVICE_VERSION_2007_SP1:
			case $RemoteStore::SERVICE_VERSION_2009:
				$this->RemoteTasksService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteTasksService2007::class);
				break;
			default:
				$this->RemoteTasksService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteTasksService::class);
				break;
		}
		// configure remote service
		$this->RemoteTasksService->configure($configuration, $RemoteStore);
		// configure local service
		$this->LocalTasksService->configure($configuration, $this->LocalStore, $this->LocalFileStore->getUserFolder($configuration->UserId));
		
	}

	/**
	 * Perform harmonization for all events collection correlations
	 * 
	 * @since Release 1.0.0
	 *
	 * @return HarmonizationStatisticsObject
	 */
	public function performHarmonization($correlation) : object {
		
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
			$this->logger->debug('EWS - Deleted tasks collection correlation for ' . $this->Configuration->UserId . ' due to missing Remote ID or Local ID');
			return $statistics;
		}
		// delete and skip collection correlation if local collection is missing
		$lcollection = $this->LocalTasksService->fetchCollection($lcid);
		if (!isset($lcollection) || ($lcollection->Id != $lcid)) {
			$this->CorrelationsService->deleteByAffiliationId($this->Configuration->UserId, $caid);
			$this->CorrelationsService->delete($correlation);
			$this->logger->debug('EWS - Deleted tasks collection correlation for ' . $this->Configuration->UserId . ' due to missing Local Collection');
			return $statistics;
		}
		// delete and skip collection correlation if remote collection is missing
		try {
			$rcollection = $this->RemoteTasksService->fetchCollection($rcid);
		} 
		catch (\Throwable $th) {
			if (str_contains($th->getMessage(), 'Remote Error: ErrorItemNotFound')) {
				$this->CorrelationsService->deleteByAffiliationId($this->Configuration->UserId, $caid);
				$this->CorrelationsService->delete($correlation);
				$this->logger->debug('EWS - Deleted tasks collection correlation for ' . $this->Configuration->UserId . ' due to missing Remote Collection');
				return $statistics;
			}
			else {
				throw $th;
			}
		}
		// retrieve list of local changed objects
		$lCollectionChanges = $this->LocalTasksService->fetchCollectionChanges($correlation->getloid(), (string) $correlation->getlostate());
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
		$rCollectionChanges = $this->RemoteTasksService->fetchCollectionChanges($correlation->getroid(), (string) $correlation->getrostate());
		// process remote created objects
		foreach ($rCollectionChanges->Create as $changed) {
			// evaluate if change is a task item and has an id
			if (!isset($changed->Task) || empty($changed->Task->ItemId->Id)) {
				continue;
			}
			// process create
			$as = $this->harmonizeRemoteAltered(
				$this->Configuration->UserId, 
				$rcid, 
				$changed->Task->ItemId->Id, 
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
			// evaluate if change is a task item and has an id
			if (!isset($changed->Task) || empty($changed->Task->ItemId->Id)) {
				continue;
			}
			// process update
			$as = $this->harmonizeRemoteAltered(
				$this->Configuration->UserId, 
				$rcid, 
				$changed->Task->ItemId->Id, 
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
			// evaluate if change has a item id
			if (empty($changed->ItemId->Id)) {
				continue;
			}
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
		$lo = $this->LocalTasksService->fetchCollectionItem($lcid, $loid);
		// evaluate, if local event object was returned
		if (!($lo instanceof \OCA\EWS\Objects\TaskObject)) {
			// return status of action
			return $status;
		}
		// try to retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByLocalId($uid, CorrelationsService::TaskObject, $loid, $lcid);
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
			$ro = $this->RemoteTasksService->fetchCollectionItem($ci->getroid());
		}
		// if remote object retrieve failed, try to retrieve remote object by UUID
		if (!isset($ro) && !empty($lo->UUID)) {
			// retrieve list of remote ids and uids
			if (!isset($this->RemoteUUIDs)) {
				$this->RemoteUUIDs = $this->RemoteTasksService->fetchCollectionItemsUUID($rcid);
			}
			// search for uuid
			$k = array_search($lo->UUID, array_column($this->RemoteUUIDs, 'UUID'));
			if ($k !== false) {
				// retrieve remote event object
				$ro = $this->RemoteTasksService->fetchCollectionItem($this->RemoteUUIDs[$k]['ID']);
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
				if ($this->Configuration->TasksPrevalence == 'L' || 
					($this->Configuration->TasksPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteTasksService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteTasksService->updateCollectionItem($rcid, $ro->ID, $ro->State, $lo);
					// assign status
					$status = 'RU'; // Remote Update
				}
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->TasksPrevalence == 'R' || 
					($this->Configuration->TasksPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalTasksService->updateCollectionItem($lcid, $lo->ID, $ro);
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
				if ($this->Configuration->TasksPrevalence == 'L' || 
				   ($this->Configuration->TasksPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteTasksService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteTasksService->updateCollectionItem($rcid, $ro->ID, $ro->State, $lo);
					// assign status
					$status = 'RU'; // Rocal Update
				}
				// update local object if
				// remote wins mode selected
				// chronology wins mode selected and remote object is newer
				if ($this->Configuration->TasksPrevalence == 'R' || 
				   ($this->Configuration->TasksPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalTasksService->updateCollectionItem($lcid, $lo->ID, $ro);
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
				$this->RemoteTasksService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
				// update remote object
				$ro = $this->RemoteTasksService->updateCollectionItem($rcid, $ro->ID, $ro->State, $lo);
				// assign status
				$status = 'RU'; // Remote Update
			}
		}
		else {
			// create remote object
			$ro = $this->RemoteTasksService->createCollectionItem($rcid, $lo);
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
			$ci->settype(CorrelationsService::TaskObject); // Correlation Type
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
		$ci = $this->CorrelationsService->findByLocalId($uid, CorrelationsService::TaskObject, $loid, $lcid);
		// evaluate correlation object
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			// destroy remote object
			$rs = $this->RemoteTasksService->deleteCollectionItem($ci->getroid());
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
		$ro = $this->RemoteTasksService->fetchCollectionItem($roid);
		// evaluate, if remote event object was returned
		if (!($ro instanceof \OCA\EWS\Objects\TaskObject)) {
			// return status of action
			return $status;
		}
		// retrieve correlation for remote and local object
		$ci = $this->CorrelationsService->findByRemoteId($uid, CorrelationsService::TaskObject, $roid, $rcid);
		// if correlation exists, compare update state to correlation state and stop processing if they match
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getrostate() == $ro->State) {
			// return status of action
			return $status;
		}
		// if correlation exists, try to retrieve local object
		if ($ci instanceof \OCA\EWS\Db\Correlation && 
			$ci->getloid()) {			
			$lo = $this->LocalTasksService->fetchCollectionItem($lcid, $ci->getloid());
		}
		// if local object retrieve failed, try to retrieve local object by UUID
		if (!isset($lo) && !empty($ro->UUID)) {
			$lo = $this->LocalTasksService->findCollectionItemByUUID($lcid, $ro->UUID);
			// if local object was found by uuid, check if a correlation exist for the local object to another remote object
			// work around for duplicate uuid's in remote objects
			if (isset($lo)) {
				// retrieve correlation for local object
				$c = $this->CorrelationsService->findByLocalId($uid, 'TO', $lo->ID, $lcid);
				// if correlation exists and remote object id is different
				// then this another object with a duplicate UUID
				if (isset($c) && ($c->getroid() != $ro->ID)) {
					// clear local object to force a new local object to be created
					$lo = null;
					// clear remote object uuid to force new uuid to be created
					$ro->UUID = null;
				}
			}
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
				if ($this->Configuration->TasksPrevalence == 'R' || 
					($this->Configuration->TasksPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalTasksService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->TasksPrevalence == 'L' || 
					($this->Configuration->TasksPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteTasksService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteTasksService->updateCollectionItem($rcid, $ro->ID, $ro->State, $lo);
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
				if ($this->Configuration->TasksPrevalence == 'R' || 
				   ($this->Configuration->TasksPrevalence == 'C' && ($ro->ModifiedOn > $lo->ModifiedOn))) {
					// update local object
					$lo = $this->LocalTasksService->updateCollectionItem($lcid, $lo->ID, $ro);
					// assign status
					$status = 'LU'; // Local Update
				}
				// update remote object if
				// local wins mode selected
				// chronology wins mode selected and local object is newer
				if ($this->Configuration->TasksPrevalence == 'L' || 
				   ($this->Configuration->TasksPrevalence == 'C' && ($lo->ModifiedOn > $ro->ModifiedOn))) {
					// delete all previous attachment(s) in remote store
					// work around for missing update command in ews
					$this->RemoteTasksService->deleteCollectionItemAttachment(array_column($ro->Attachments, 'Id'));
					// update remote object
					$ro = $this->RemoteTasksService->updateCollectionItem($rcid, $ro->ID, $ro->State, $lo);
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
				$lo = $this->LocalTasksService->updateCollectionItem($lcid, $lo->ID, $ro);
				// assign status
				$status = 'LU'; // Local Update
			}
		}
		else {
			// create local object
			$lo = $this->LocalTasksService->createCollectionItem($lcid, $ro);
			// update remote object uuid if was missing
			if (empty($ro->UUID)) {
				$rs = $this->RemoteTasksService->updateCollectionItemUUID($rcid, $ro->ID, $ro->State, $lo->UUID);
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
			$ci->settype(CorrelationsService::TaskObject); // Correlation Type
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
		$ci = $this->CorrelationsService->findByRemoteId($uid, CorrelationsService::TaskObject, $roid, $rcid);
		// evaluate correlation object
		if ($ci instanceof \OCA\EWS\Db\Correlation) {
			// destroy local object
			$rs = $this->LocalTasksService->deleteCollectionItem($ci->getlcid(), $ci->getloid());
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
	
}
