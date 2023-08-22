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

namespace OCA\EWS\Service\Local;

use Datetime;
use DateTimeZone;
use DateInterval;
use Psr\Log\LoggerInterface;
use OC\Files\Node\LazyUserFolder;
use OCA\DAV\CalDAV\CalDavBackend;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Db\TasksUtile;
use OCA\EWS\Objects\TaskCollectionObject;
use OCA\EWS\Objects\TaskObject;
use OCA\EWS\Objects\TaskAttachmentObject;

use Sabre\VObject\Reader;
use Sabre\VObject\Component\VTodo;

class LocalTasksService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
    /**
	 * @var DateTimeZone
	 */
	private ?DateTimeZone $DefaultTimeZone = null;
    /**
	 * @var DateTimeZone
	 */
	public ?DateTimeZone $UserTimeZone = null;
    /**
	 * @var CalDavBackend
	 */
	public ?CalDavBackend $DataStore = null;
    /**
	 * @var LazyUserFolder
	 */
	public ?LazyUserFolder $FileStore = null;

	public function __construct (string $appName, LoggerInterface $logger, TasksUtile $TasksUtile) {
		$this->logger = $logger;
        $this->TasksUtile = $TasksUtile;
        $this->DefaultTimeZone = new DateTimeZone(date_default_timezone_get());
	}

	/**
     * retrieve information for specific collection from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $uid - User ID
	 * 
	 * @return array of collections
	 */
	public function listCollections(string $uid): array {

        // retrieve all local collections 
        $collections = $this->DataStore->getCalendarsForUser('principals/users/' . $uid);
		// construct collections list
		$data = array();
		foreach ($collections as $entry) {
            if (isset($entry['{urn:ietf:params:xml:ns:caldav}supported-calendar-component-set'])) {
                if (in_array('VTODO', $entry['{urn:ietf:params:xml:ns:caldav}supported-calendar-component-set']->getValue())) {
                    $data[] = array('id' => $entry['id'], 'name' => $entry['{DAV:}displayname'], 'uri' => $entry['uri']);
                }
            }
		}
        // return collections list
		return $data;

    }
	
    /**
     * retrieve properties for specific collection from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection Id
	 * 
	 * @return TaskCollectionObject of collection properties
	 */
	public function fetchCollection(string $cid): ?TaskCollectionObject {

        // retrieve collection properties
        $ec = $this->DataStore->getCalendarById($cid);

        if (isset($ec)) {
            return new TaskCollectionObject(
                $ec['id'],
                $ec['{DAV:}displayname'],
                $ec['{http://sabredav.org/ns}sync-token']
            );
        }
        else {
            return null;
        }

    }

	/**
     * create collection in local storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $uid - User ID
	 * @param string $cid - Collection URI
     * @param string $name - Collection Name
	 * 
	 * @return TaskCollectionObject
	 */
	public function createCollection(string $uid, string $cid, string $name): ?TaskCollectionObject {

        // check for user id and collection - must contain to create
        if (!empty($uid) && !empty($cid)) {
            // create item in data store
            $result = $this->DataStore->createCalendar(
                'principals/users/' . $uid, 
                $cid, 
                array('{DAV:}displayname' => $name)
            );
        }
        // return collection object or null
        if (isset($result)) {
            return $this->fetchCollection($result);
        } else {
            return null;
        }

    }

    /**
     * delete collection from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $mode - True for permanently / False - for Recoverable
	 * 
	 * @return bool true - successfully delete / false - failed to delete
	 */
	public function deleteCollection(string $cid, bool $mode = false): bool {

        // check for id - must contain id to delete
        if (!empty($cid)) {
            // delete item in data store
            $result = $this->DataStore->deleteCalendar($cid, $mode);
        }
        // return operation result
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * retrieve changes for specific collection from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection Id
     * @param string $state - Collection Id
	 * 
	 * @return array of collection changes
	 */
	public function fetchCollectionChanges(string $cid, string $state): array {

        // retrieve collection chamges
        $lcc = $this->DataStore->getChangesForCalendar($cid, $state, null, null);
        // return collection chamges
		return $lcc;

    }
	
    /**
     * find collection item by uuid in local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $uuid - Item UUID
	 * 
	 * @return TaskObject TaskObject - successfully retrieved / null - failed to retrieve
	 */
	public function findCollectionItemByUUID(string $cid, string $uuid): ?TaskObject {
        
        // search data store for object
		$lo = $this->TasksUtile->findByUUID($cid, $uuid, 'VTODO');
        // validate result
        if (is_array($lo) && count($lo) > 0) {
            $lo = $lo[0];
            // convert string to vtask object
            $to = Reader::read($lo['calendardata']);
            // convert to task object
            if (isset($to->VTODO)) {
                $to = $this->toTaskObject($to->VTODO);
                $to->ID = $lo['uri'];
                $to->UUID = $lo['uid'];
                $to->CID = $lo['calendarid'];
                $to->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
                $to->State = trim($lo['etag'],'"');
                // attachments
                if (count($to->Attachments) > 0) {
                    // retrieve attachments from local data store
                    $to->Attachments = $this->fetchCollectionItemAttachment(array_column($to->Attachments, 'Id'), 'F');
                }
                // return task object
                return $to;
            }
            else {
                return null;
            }
        } else {
            // return null
            return null;
        }

    }
	
    /**
     * retrieve collection item from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
	 * 
	 * @return TaskObject TaskObject - successfully retrieved / null - failed to retrieve
	 */
	public function fetchCollectionItem(string $cid, string $iid): ?TaskObject {

        // retrieve collection item
        $lo = $this->DataStore->getCalendarObject($cid, $iid);
        // return task object or null
        if ($lo) {
            // read item data
            $to = Reader::read($lo['calendardata']);
            // convert to task object
            if (isset($to->VTODO)) {
                $to = $this->toTaskObject($to->VTODO);
                $to->ID = $lo['uri'];
                $to->CID = $lo['calendarid'];
                $to->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
                $to->State = trim($lo['etag'],'"');

                // attachments
                if (count($to->Attachments) > 0) {
                    // retrieve attachments from local data store
                    $to->Attachments = $this->fetchCollectionItemAttachment(array_column($to->Attachments, 'Id'), 'F');
                }
                // return task object
                return $to;
            }
            else {
                return null;
            }
        } else {
            // return null
            return null;
        }

    }

    /**
     * create collection item in local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param TaskObject $so - Item Data
	 * 
	 * @return TaskObject
	 */
	public function createCollectionItem(string $cid, TaskObject $so): ?TaskObject {

        // clone source object
        $to = clone $so;
        // deposit attachment(s)
        if (count($to->Attachments) > 0) {
            // create or update attachements in local data store
            $to->Attachments = $this->createCollectionItemAttachment(
                $to->StartsOn->format('Y-m-d H.i.s') . " " . \OCA\EWS\Utile\Sanitizer::folder($to->Label, true, true), 
                $to->Attachments
            );
        }
        //evaluate if task object contains uuid
        if (empty($to->UUID)) {
            // generate uuid if missing
            $to->UUID = \OCA\EWS\Utile\UUID::v4();
        }
        // convert task object to vtodo object
        $vt = $this->fromTaskObject($to);
        // generate item id
        $vtid = \OCA\EWS\Utile\UUID::v4() . '.ics';
        // create item in data store
        $rs = $this->DataStore->createCalendarObject(
            $cid, 
            $vtid, 
            "BEGIN:VCALENDAR\nVERSION:2.0\n" . $vt->serialize() . "\nEND:VCALENDAR"
        );
        // return task object or null
        if (isset($rs)) {
            $to->ID = $vtid;
            $to->CID = $cid;
            $to->State = trim($rs,'"');
            return $to;
        } else {
            return null;
        }
        
    }

    /**
     * update collection item in local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
     * @param TaskObject $so - Source Data
	 * 
	 * @return TaskObject
	 */
	public function updateCollectionItem(string $cid, string $iid, TaskObject $so): ?TaskObject {

        // check for id - must contain id to update
        if (!empty($iid)) {
            // clone source object
            $to = clone $so;
            // deposit attachment(s)
            if (count($to->Attachments) > 0) {
                // create or update attachements in local data store
                $to->Attachments = $this->createCollectionItemAttachment(
                    $to->StartsOn->format('Y-m-d H.i.s') . " " . \OCA\EWS\Utile\Sanitizer::folder($to->Label, true, true), 
                    $to->Attachments
                );
            }
            // convert task object to vtodo object
            $vt = $this->fromTaskObject($to);
            // update item in data store
            $rs = $this->DataStore->updateCalendarObject(
                $cid, 
                $iid, 
                "BEGIN:VCALENDAR\nVERSION:2.0\n" . $vt->serialize() . "\nEND:VCALENDAR"
            );
        }
        // return task object or null
        if (isset($rs)) {
            $to->ID = $iid;
            $to->CID = $cid;
			$to->State = trim($rs,'"');
            return $to;
        } else {
            return null;
        }

    }

    /**
     * delete collection item from local storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
	 * 
	 * @return bool true - successfully delete / False - failed to delete
	 */
	public function deleteCollectionItem(string $cid, string $iid): bool {

        // check for id - must contain id to delete
        if (!empty($iid)) {
            // delete item in data store
            $result = $this->DataStore->deleteCalendarObject($cid, $iid);
        }
        // return operation result
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * retrieve collection item attachment from local storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $uid - User ID
     * @param string $batch - Collection of Id's
     * @param string $flag - I - File Information / F - File Information + Content
	 * 
	 * @return TaskAttachmentObject
	 */
	public function fetchCollectionItemAttachment(array $batch, string $flag = 'I'): array {

        // check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
        // construct response collection place holder
        $rc = array();
        // process collection of objects
        foreach ($batch as $key => $entry) {
            try {
                // 
                $fo = $this->FileStore->getById($entry);
                if($fo[0] instanceof \OCP\Files\File) {
                    $ao = new TaskAttachmentObject('D');
                    $ao->Id = $fo[0]->getFileInfo()->getId();
                    $ao->Name = $fo[0]->getFileInfo()->getName();
                    $ao->Type = $fo[0]->getFileInfo()->getMimetype();
                    $ao->Size = $fo[0]->getFileInfo()->getSize();
                    if ($flag == 'F') {
                        $ao->Data = $fo[0]->getContent();
                        $ao->Encoding = 'B';
                    }
                    // insert attachment object in response collection
                    $rc[] = $ao;
                }
            } catch(\OCP\Files\NotFoundException $e) {
                throw new StorageException('File does not exist');
            }
        }
        // return response collection
        return $rc;

    }

    /**
     * create collection item attachment in local storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $uid - User ID
     * @param string $fn - Folder Name to save attachments
     * @param array $batch - Collection of TaskAttachmentObject(s) objects
	 * 
	 * @return string
	 */
	public function createCollectionItemAttachment(string $fn, array $batch): array {

        // check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
        // construct response collection place holder
        $rc = array();
        // process collection of objects
        foreach ($batch as $key => $entry) {
            // check if file exists and write to it if possible
            try {
                // construct folder location
                $fl = 'Tasks/' . $fn;
                // check if folder exists
                if (!$this->FileStore->nodeExists($fl)) {
                    // create folder if missing
                    $this->FileStore->newFolder($fl);
                    $this->FileStore->unlock($fl);
                } 
                // cunstruct file location
                $fl = $fl . '/' . $entry->Name;
                // check if file exists
                if (!$this->FileStore->nodeExists($fl)) {
                    // create file
                    $fo = $this->FileStore->newFile($fl, $entry->Data);
                    $this->FileStore->unlock($fl);
                } else {
                    // select file
                    $fo = $this->FileStore->get($fl);
                    // update file
                    $fo->putContent((string)$entry->Data);
                    $this->FileStore->unlock($fl);
                }

                $ao = clone $entry;
                $ao->Id = $fo->getId();
                $ao->Data = '/' . $fl;
                $ao->Size = $fo->getSize();
                $ao->Store = 'D';

                $rc[] = $ao;
                
                unset($fl);
                unset($fo);

            } catch(\OCP\Files\NotPermittedException $e) {
                // you have to create this exception by yourself ;)
                throw new StorageException('Cant write to file');
            } catch (Exception $e) {
                throw $e;
            }
        }
        // return results collection
        return $rc;

    }

    /**
     * delete collection item attachment from local storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $aid - Attachment ID
	 * 
	 * @return bool true - successfully delete / False - failed to delete
	 */
	public function deleteCollectionItemAttachment(array $batch): array {

        // check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
        
        // TODO: add delete code

        return array();
    }

    /**
     * convert vtodo object to task object
     * 
     * @since Release 1.0.0
     * 
	 * @param VTodo $vo - source object
	 * 
	 * @return TaskObject converted object
	 */
	public function toTaskObject(VTodo $vo): TaskObject {
		
        // construct task object
		$to = new TaskObject();
        // Origin
		$to->Origin = 'L';
        // UUID
        if (isset($vo->UID)) {
            $to->UUID = trim($vo->UID->getValue());
        }
        // Creation Date
        if (isset($vo->CREATED)) {
            $to->CreatedOn = new DateTime($vo->CREATED->getValue());
        }
        // Modification Date
        if (isset($vo->{'LAST-MODIFIED'})) {
            $to->ModifiedOn = new DateTime($vo->{'LAST-MODIFIED'}->getValue());
        }
        // Starts Date/Time
        if (isset($vo->DTSTART)) {
            if (isset($vo->DTSTART->parameters['TZID'])) {
                $tz = new DateTimeZone($vo->DTSTART->parameters['TZID']->getValue());
            }
            elseif (str_contains($vo->DTSTART, 'Z')) {
                $tz = new DateTimeZone('UTC');
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $tz = $this->UserTimeZone;
            }
            else {
                $tz = $this->DefaultTimeZone;
            }
            $to->StartsOn = new DateTime($vo->DTSTART->getValue(), $tz);
            unset($tz);
        }
        // DUE Date/Time
        if (isset($vo->DUE)) {
            if (isset($vo->DUE->parameters['TZID'])) {
                $tz = new DateTimeZone($vo->DUE->parameters['TZID']->getValue());
            }
            elseif (str_contains($vo->DUE, 'Z')) {
                $tz = new DateTimeZone('UTC');
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $tz = $this->UserTimeZone;
            }
            else {
                $tz = $this->DefaultTimeZone;
            }
            $to->DueOn = new DateTime($vo->DUE->getValue(), $tz);
            unset($tz);
        }
        // Label
        if (isset($vo->SUMMARY)) {
            $to->Label = trim($vo->SUMMARY->getValue());
        }
        // Notes
        if (isset($vo->DESCRIPTION)) {
            if (!empty(trim($vo->DESCRIPTION->getValue()))) {
                $to->Notes = trim($vo->DESCRIPTION->getValue());
            }
        }
        // Progress
        if (isset($vo->{'PERCENT-COMPLETE'})) {
            $to->Progress = trim($vo->{'PERCENT-COMPLETE'}->getValue());
        }
        // Status
        if (isset($vo->STATUS)) {
            $to->Status = $this->fromStatus($vo->STATUS->getValue());;
        }
        // Priority
        if (isset($vo->PRIORITY)) {
            $to->Priority = trim($vo->PRIORITY->getValue());
        }
        // Sensitivity
        if (isset($vo->CLASS)) {
            $to->Sensitivity = $this->fromClass($vo->CLASS->getValue());
        }
        // Tag(s)
        if (isset($vo->CATEGORIES)) {
            foreach($vo->CATEGORIES->getParts() as $entry) {
                $to->addTag(
                    trim($entry)
                );
            }
        }
        // Notifications
        if (isset($vo->VALARM)) {
            foreach($vo->VALARM->TRIGGER as $entry) {
                if ($vo->VALARM->ACTION->count() > 0) {
                    // Notifications Type
                    $t = $this->fromAlarmAction($vo->VALARM->ACTION[0]->getValue());

                    if ($t = 'D') {
                        if (!empty($vo->VALARM->TRIGGER[0]->getValue())) {
                            if (isset($vo->VALARM->TRIGGER[0]->parameters['RELATED'])) {
                                $p = 'R';
                                $w = $this->fromDurationPeriod($vo->VALARM->TRIGGER[0]->getValue());
                            }
                            elseif (isset($vo->VALARM->TRIGGER[0]->parameters['VALUE'])) {
                                $p = 'A';
                                $w = new DateTime($vo->VALARM->TRIGGER[0]->getValue());
                            }
                            $to->addNotification(
                                $t,
                                $p,
                                $w
                            );
                            unset($p);
                            unset($w);
                        }
                    }
                    unset($t);
                }
            }
        }
        // Attachment(s)
        if (isset($vo->ATTACH)) {
            foreach($vo->ATTACH as $entry) {
                if (isset($entry->parameters['X-NC-FILE-ID'])) {
                    $fs = 'D';
                    $fi = $entry->parameters['X-NC-FILE-ID']->getValue();
                    $fn = $entry->parameters['FILENAME']->getValue();
                    $ft = $entry->parameters['FMTTYPE']->getValue();
                    $fd = $entry->parameters['FILENAME']->getValue();

                    $to->addAttachment(
                        $fs,
                        $fi,
                        $fn,
                        $ft,
                        'B',
                        null,
                        $fd
                    );
                }
            }
        }
        // Occurrence
        if (isset($vo->RRULE)) {
            $parts = $vo->RRULE->getParts();
            if (isset($parts['FREQ'])) {
                $to->Occurrence->Precision = $this->fromFrequency($parts['FREQ']);
            }
            if (isset($parts['INTERVAL'])) {
                $to->Occurrence->Interval = $parts['INTERVAL'];
            }
            if (isset($parts['COUNT'])) {
                $to->Occurrence->Iterations = $parts['COUNT'];
            }
            if (isset($parts['UNTIL'])) {
                $to->Occurrence->Concludes = new DateTime($parts['UNTIL']);
            }
            if (isset($parts['BYDAY'])) {
                if (is_array($parts['BYDAY'])) {
                    $to->Occurrence->OnDayOfWeek = $this->fromByDay($parts['BYDAY']);
                }
                else {
                    $to->Occurrence->OnDayOfWeek = $this->fromByDay(array($parts['BYDAY']));
                }
            }
            if (isset($parts['BYMONTH'])) {
                if (is_array($parts['BYMONTH'])) {
                    $to->Occurrence->OnMonthOfYear = $parts['BYMONTH'];
                }
                else {
                    $to->Occurrence->OnMonthOfYear = array($parts['BYMONTH']);
                }
            }
            if (isset($parts['BYMONTHDAY'])) {
                if (is_array($parts['BYMONTHDAY'])) {
                    $to->Occurrence->OnDayOfMonth = $parts['BYMONTHDAY'];
                }
                else {
                    $to->Occurrence->OnDayOfMonth = array($parts['BYMONTHDAY']);
                }
            }
            if (isset($parts['BYYEARDAY'])) {
                if (is_array($parts['BYYEARDAY'])) {
                    $to->Occurrence->OnDayOfYear = $parts['BYYEARDAY'];
                }
                else {
                    $to->Occurrence->OnDayOfYear = array($parts['BYYEARDAY']);
                }
            }
            if (isset($parts['BYSETPOS'])) {
                $to->Occurrence->Pattern = 'R';
                $to->Occurrence->OnWeekOfMonth = array($parts['BYSETPOS']);
            } else {
                $to->Occurrence->Pattern = 'A';
            }
            // Excludes
            if (isset($vo->EXDATE)) {
                foreach ($vo->EXDATE as $entry) {
                    if (isset($entry->parameters['TZID'])) {
                        $tz = new DateTimeZone($entry->parameters['TZID']->getValue());
                    }
                    elseif (str_contains($entry->getValue(), 'Z')) {
                        $tz = new DateTimeZone('UTC');
                    }
                    elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                        $tz = $this->UserTimeZone;
                    }
                    else {
                        $tz = $this->DefaultTimeZone;
                    }
                    $to->Occurrence->Excludes[] = new DateTime($entry->getValue(), $tz);
                }
            }
        }
        
		// return task object
		return $to;
        
    }

    /**
     * Convert task object to vtask object
     * 
     * @since Release 1.0.0
     * 
	 * @param TaskObject $vo - source object
	 * 
	 * @return VTodo converted object
	 */
    public function fromTaskObject(TaskObject $to): VTodo{

        // construct vtask object
        $vo = new \Sabre\VObject\Component\VCalendar();
        $vo = $vo->createComponent('VTODO');
        // UID
        if ($to->UUID) {
            $vo->UID->setValue($to->UUID);
        }
        // Starts Date/Time
        if (isset($to->StartsOn)) {
            $vo->add('DTSTART', $to->StartsOn->format('Ymd\THis'));
        }
        // Ends Date/Time
        if (isset($to->DueOn)) {
            $vo->add('DUE', $to->DueOn->format('Ymd\THis'));
        }
        // Label
        if ($to->Label) {
            $vo->add('SUMMARY',$to->Label);
        }
        // Notes
        if (isset($to->Notes)) {
            $vo->add('DESCRIPTION', $to->Notes);
        }
        // Progress
        if (isset($to->Progress)) {
            $vo->add('PERCENT-COMPLETE', $to->Progress);
        }
        // Status
        if (isset($to->Status)) {
            $vo->add('STATUS', $this->toStatus($to->Status));
        }
        // Priority
        if (isset($to->Priority)) {
            $vo->add('PRIORITY', $to->Priority);
        }
        // Sensitivity
        if (isset($to->Sensitivity)) {
            $vo->add('CLASS', $this->toClass($to->Sensitivity));
        }
        // Tag(s)
        if (count($to->Tags) > 0) {
            $vo->add('CATEGORIES', $to->Tags);
        }
        // Attachment(s)
        if (count($to->Attachments) > 0) {
            foreach($to->Attachments as $entry) {
                // Data Store
                if ($entry->Store == 'D' && !empty($entry->Id)) {
                    $p = array();
                    $p['X-NC-FILE-ID'] = $entry->Id;
                    $p['FILENAME'] = $entry->Data;
                    $p['FMTTYPE'] = $entry->Type;
                    $vo->add('ATTACH', "/f/" . $entry->Id, $p);
                    unset($p);
                }
                // Referance
                elseif ($entry->Store == 'R' && !empty($entry->Data)) {
                    $p = array();
                    $p['FMTTYPE'] = $entry->Type;
                    $vo->add('ATTACH', $entry->Data, $p);
                    unset($p);
                }
                // Enclosed
                elseif (!empty($entry->Data)) {
                    $p = array();
                    $p['FMTTYPE'] = $entry->Type;
                    $p['ENCODING'] = 'BASE64';
                    $p['VALUE'] = 'BINARY';
                    unset($p);
                    if ($entry->Encoding == 'B64') {
                        $vo->add(
                            'ATTACH',
                            'X-FILENAME="' . $entry->Name . '":' . $entry->Data,
                            $p
                        );
                    }
                    else {
                        $vo->add(
                            'ATTACH',
                            'X-FILENAME="' . $entry->Name . '":' .  base64_encode($entry->Data),
                            $p
                        );
                    }
                }
                
            }
        }
        // Notifications
        if (count($to->Notifications) > 0) {
            foreach($to->Notifications as $entry) {
                $vo->add('VALARM');
                $i= $vo->VALARM->count() - 1;
                // Notifications Type
                $vo->VALARM[$i]->add('ACTION', $this->toAlarmAction($entry->Type));
                // Notifications Pattern
                switch ($entry->Pattern) {
                    case 'R':
                        $t = $this->toDurationPeriod($entry->When);
                        $vo->VALARM[$i]->add('TRIGGER', $t, array('RELATED' => 'START'));
                        break;
                    case 'A':
                        $vo->VALARM[$i]->add('VALUE', $entry->When, array());
                        break;
                }

                unset($i);
                unset($t);
            }
        }
        // Occurrence
        if (isset($to->Occurrence->Precision)) {
            $p = array();
            // Occurrence Precision
            if (isset($to->Occurrence->Precision)) {
                $p['FREQ'] = $this->toFrequency($to->Occurrence->Precision);
            }
            // Occurrence Interval
            if (isset($to->Occurrence->Interval)) {
                $p['INTERVAL'] = $to->Occurrence->Interval;
            }
            // Occurrence Interval
            if (isset($to->Occurrence->Iterations)) {
                $p['COUNT'] = $to->Occurrence->Iterations;
            }
            // Occurrence Conclusion
            if (isset($to->Occurrence->Concludes)) {
                if ($to->Origin == 'R') {
                    // adjust for how until day is calculated
                    $p['UNTIL'] = (clone $to->Occurrence->Concludes)
                                  ->add(new DateInterval('PT24H'))->format('Ymd\THis\Z');
                }
                else {
                    $p['UNTIL'] = $to->Occurrence->Concludes->format('Ymd\THis\Z');
                }
            }
            // Occurrence Day Of Week
            if (count($to->Occurrence->OnDayOfWeek) > 0) {
                $p['BYDAY'] = $this->toByDay($to->Occurrence->OnDayOfWeek);
            }
            // Occurrence Day Of Month
            if (count($to->Occurrence->OnDayOfMonth) > 0) {
                $p['BYMONTHDAY'] = implode(',', $to->Occurrence->OnDayOfMonth);
            }
            // Occurrence Day Of Year
            if (count($to->Occurrence->OnDayOfYear) > 0) {
                $p['BYYEARDAY'] = implode(',', $to->Occurrence->OnDayOfYear);
            }
            // Occurrence Month Of Year
            if (count($to->Occurrence->OnMonthOfYear) > 0) {
                $p['BYMONTH'] = implode(',', $to->Occurrence->OnMonthOfYear);
            }
            // Occurrence Relative
            if ($to->Occurrence->Pattern == 'R') {
                $p['BYSETPOS'] = implode(',', $to->Occurrence->OnWeekOfMonth);
            }
            // create attribute
            $vo->add('RRULE', $p);
            unset($p);
            // Occurrence Excludes
            if (count($to->Occurrence->Excludes) > 0) {
                foreach ($to->Occurrence->Excludes as $entry) {
                    if ($entry instanceof \DateTime) {
                        $tz = $entry->getTimeZone()->getName();  
                    }
                    elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                        $tz = $this->UserTimeZone->getName();
                    }
                    else {
                        $tz = $this->DefaultTimeZone->getName();
                    }
                    // apply time zone
                    $dt = clone $entry;
                    $dt->setTimezone(new DateTimeZone($tz));
                    // create element
                    $vo->add(
                        'EXDATE', 
                        $dt->format('Ymd\THis'),
                        array('TZID' => $tz)
                    );
                    unset($dt);
                    unset($tz);
                }
            }
        }

        return $vo;

    }

    /**
     * convert local frequency to task object occurrence precision
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $frequency - local frequency value
	 * 
	 * @return int task object occurrence precision value
	 */
    private function fromFrequency(?string $frequency): string {
		
        // frequency conversion reference
		$_tm = array(
			'DAILY' => 'D',
			'WEEKLY' => 'W',
			'MONTHLY' => 'M',
            'YEARLY' => 'Y',
			'HOURLY' => 'H',
			'MINUTELY' => 'I',
            'SECONDLY' => 'S',
		);
        // evaluate if frequency value exists
		if (isset($_tm[$frequency])) {
			// return converted occurrence precision value
			return $_tm[$frequency];
		} else {
            // return default occurrence precision value
			return 'D';
		}
		
	}

    /**
     * convert task object occurrence precision to local frequency
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $precision - task object occurrence precision value
	 * 
	 * @return string local frequency value
	 */
	private function toFrequency(?string $precision): string {

        // occurrence precision conversion reference
		$_tm = array(
			'D' => 'DAILY',
			'W' => 'WEEKLY',
			'M' => 'MONTHLY',
            'Y' => 'YEARLY',
			'H' => 'HOURLY',
			'I' => 'MINUTELY',
            'S' => 'SECONDLY',
		);
        // evaluate if occurrence precision value exists
		if (isset($_tm[$precision])) {
			// return converted frequency value
			return $_tm[$precision];
		} else {
            // return default frequency value
			return 'DAILY';
		}

	}

    /**
     * convert local by day to task object days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - local by day values(s)
	 * 
	 * @return array task object days of the week values(s)
	 */
    private function fromByDay(array $days): array {
        
        // days conversion reference
        $_tm = array(
            'MO' => 1,
            'TU' => 2,
            'WE' => 3,
            'TH' => 4,
            'FR' => 5,
            'SA' => 6,
            'SU' => 7
        );
        // convert day values
        foreach ($days as $key => $value) {
            if (isset($_tm[$value])) {
                $days[$key] = $_tm[$value];
            }
        }
        // return converted days
        return $days;
    }

    /**
     * convert task object days of the week to local by day
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - task object days of the week values(s)
	 * 
	 * @return string local by day values(s)
	 */
    private function toByDay(array $days): string {

        // days conversion reference
        $_tm = array(
            1 => 'MO',
            2 => 'TU',
            3 => 'WE',
            4 => 'TH',
            5 => 'FR',
            6 => 'SA',
            7 => 'SU'
        );
        // convert day values
        foreach ($days as $key => $value) {
            if (isset($_tm[$value])) {
                $days[$key] = $_tm[$value];
            }
        }
        // convert days to string
        $days = implode(',', $days);
        // return converted days
        return $days;

    }

    /**
     * convert local status to task object status
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $status - local status value
	 * 
	 * @return string task object status value
	 */
    private function fromStatus(?string $status): string {
		
        // status conversion reference
		$_tm = array(
			'NEEDS-ACTION' => 'N',
			'IN-PROCESS' => 'P',
			'COMPLETED' => 'C',
            'CANCELLED' => 'D'
		);
        // evaluate if status value exists
		if (isset($_tm[$status])) {
			// return converted status value
			return $_tm[$status];
		} else {
            // return default status value
			return 'N';
		}
		
	}

    /**
     * convert task object status to local status
     *  
     * @since Release 1.0.0
     * 
	 * @param string $status - task object status value
	 * 
	 * @return string local status value
	 */
	private function toStatus(?string $status): string {

        // status conversion reference
		$_tm = array(
			'N' => 'NEEDS-ACTION',
			'P' => 'IN-PROCESS',
            'W' => 'IN-PROCESS',
			'C' => 'COMPLETED',
			'D' => 'CANCELLED'
		);
        // evaluate if status value exists
		if (isset($_tm[$status])) {
			// return converted status value
			return $_tm[$status];
		} else {
            // return default status value
			return 'NEEDS-ACTION';
		}

	}

    /**
     * convert local class to task object sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - local class value
	 * 
	 * @return int|null task object sensitivity value
	 */
    private function fromClass(?string $level): int {
		
        // class conversion reference
		$_tm = array(
			'PUBLIC' => 0,
			'PRIVATE' => 2,
			'CONFIDENTIAL' => 3
		);
        // evaluate if class value exists
		if (isset($_tm[$level])) {
			// return converted sensitivity value
			return $_tm[$level];
		} else {
            // return default sensitivity value
			return 0;
		}
		
	}

    /**
     * convert task object sensitivity to local class
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - task object sensitivity value
	 * 
	 * @return string|null local class value
	 */
	private function toClass(?int $level): string {

        // sensitivity conversion reference
		$_tm = array(
			0 => 'PUBLIC',
			1 => 'PRIVATE',
			2 => 'PRIVATE',
			3 => 'CONFIDENTIAL'
		);
        // evaluate if sensitivity value exists
		if (isset($_tm[$level])) {
			// return converted class value
			return $_tm[$level];
		} else {
            // return default class value
			return 'PUBLIC';
		}
	}

    /**
     * convert local alarm action to task object alarm action type
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $action - local alarm action value
	 * 
	 * @return int task object alarm action type value
	 */
    private function fromAlarmAction(?string $action): string {
		
        // action conversion reference
		$_tm = array(
			'DISPLAY' => 'D',
			'EMAIL' => 'E',
			'AUDIO' => 'A'
		);
        // evaluate if action value exists
		if (isset($_tm[$action])) {
			// return converted action value
			return $_tm[$action];
		} else {
            // return default action value
			return 'D';
		}
		
	}

    /**
     * convert task object alarm type to local alram action
     *  
     * @since Release 1.0.0
     * 
	 * @param string $type - task object action type value
	 * 
	 * @return string local alarm action value
	 */
	private function toAlarmAction(?string $type): string {

        // action conversion reference
		$_tm = array(
			'D' => 'DISPLAY',
			'E' => 'EMAIL',
			'A' => 'AUDIO'
		);
        // evaluate if action value exists
		if (isset($_tm[$type])) {
			// return converted action value
			return $_tm[$type];
		} else {
            // return default action value
			return 'NEEDS-ACTION';
		}

	}

    /**
     * convert local duration period to task object date interval
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $period - local duration period value
	 * 
	 * @return DateInterval task object date interval object
	 */
    private function fromDurationPeriod(string $period): DateInterval {
		
        // evaluate if period is negative
		if (str_contains($period, '-P')) {
            $period = trim($period, '-');
            $period = new DateInterval($period);
            $period->invert = 1;
            // return date interval object
            return $period;
        }
        else {
            // return date interval object
            return new DateInterval($period);
        }
		
	}

    /**
     * convert task object date interval to local duration period
	 * 
     * @since Release 1.0.0
     * 
	 * @param DateInterval $period - task object date interval object
	 * 
	 * @return string local duration period value
	 */
	private function toDurationPeriod(DateInterval $period): string {

		if ($period->y > 0) { return $period->format("%rP%yY%mM%dDT%hH%iM"); }
        elseif ($period->m > 0) { return $period->format("%rP%mM%dDT%hH%iM"); }
        elseif ($period->d > 0) { return $period->format("%rP%dDT%hH%iM"); }
        elseif ($period->h > 0) { return $period->format("%rPT%hH%iM"); }
        else { return $period->format("%rPT%iM"); }

	}

}
