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
use OCA\EWS\Db\EventsUtile;
use OCA\EWS\Objects\EventCollectionObject;
use OCA\EWS\Objects\EventObject;
use OCA\EWS\Objects\EventAttachmentObject;

use Sabre\VObject\Reader;
use Sabre\VObject\Component\VEvent;

class LocalEventsService {
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

	public function __construct (string $appName, LoggerInterface $logger, EventsUtile $EventsUtile) {
		$this->logger = $logger;
        $this->EventsUtile = $EventsUtile;
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
                if (in_array('VEVENT', $entry['{urn:ietf:params:xml:ns:caldav}supported-calendar-component-set']->getValue())) {
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
	 * @return EventCollectionObject of collection properties
	 */
	public function fetchCollection(string $cid): ?EventCollectionObject {

        // retrieve collection properties
        $ec = $this->DataStore->getCalendarById($cid);

        if (isset($ec)) {
            return new EventCollectionObject(
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
	 * @return EventCollectionObject
	 */
	public function createCollection(string $uid, string $cid, string $name): ?EventCollectionObject {

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
	 * @return EventObject EventObject - successfully retrieved / null - failed to retrieve
	 */
	public function findCollectionItemByUUID(string $cid, string $uuid): ?EventObject {
        
        // search data store for object
		$lo = $this->EventsUtile->findByUUID($cid, $uuid, 'VEVENT');
        // validate result
        if (is_array($lo) && count($lo) > 0) {
            $lo = $lo[0];
            // convert string to vevent object
            $eo = Reader::read($lo['calendardata']);
            // convert to event object
            if (isset($eo->VEVENT)) {
                $eo = $this->toEventObject($eo->VEVENT);
                $eo->ID = $lo['uri'];
                $eo->UUID = $lo['uid'];
                $eo->CID = $lo['calendarid'];
                $eo->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
                $eo->State = trim($lo['etag'],'"');
                // attachments
                if (count($eo->Attachments) > 0) {
                    // retrieve attachments from local data store
                    $eo->Attachments = $this->fetchCollectionItemAttachment(array_column($eo->Attachments, 'Id'), 'F');
                }
                // return event object
                return $eo;
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
	 * @return EventObject EventObject - successfully retrieved / null - failed to retrieve
	 */
	public function fetchCollectionItem(string $cid, string $iid): ?EventObject {

        // retrieve collection item
        $lo = $this->DataStore->getCalendarObject($cid, $iid);
        // return event object or null
        if ($lo) {
            // read item data
            $eo = Reader::read($lo['calendardata']);
            // convert to event object
            if (isset($eo->VEVENT)) {
                $eo = $this->toEventObject($eo->VEVENT);
                $eo->ID = $lo['uri'];
                $eo->CID = $lo['calendarid'];
                $eo->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
                $eo->State = trim($lo['etag'],'"');

                // attachments
                if (count($eo->Attachments) > 0) {
                    // retrieve attachments from local data store
                    $eo->Attachments = $this->fetchCollectionItemAttachment(array_column($eo->Attachments, 'Id'), 'F');
                }
                // return event object
                return $eo;
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
     * @param EventObject $eo - Item Data
	 * 
	 * @return EventObject
	 */
	public function createCollectionItem(string $cid, EventObject $so): ?EventObject {

        // clone source object
        $eo = clone $so;
        // deposit attachment(s)
        if (count($eo->Attachments) > 0) {
            // create or update attachements in local data store
            $eo->Attachments = $this->createCollectionItemAttachment(
                $eo->StartsOn->format('Y-m-d H.i.s') . " " . \OCA\EWS\Utile\Sanitizer::folder($eo->Label, true, true), 
                $eo->Attachments
            );
        }
        //evaluate if task object contains uuid
        if (empty($eo->UUID)) {
            // generate uuid if missing
            $eo->UUID = \OCA\EWS\Utile\UUID::v4();
        }
        // convert event object to vevent object
        $ve = $this->fromEventObject($eo);
        // generate item id
        $veid = \OCA\EWS\Utile\UUID::v4() . '.ics';
        // create item in data store
        $rs = $this->DataStore->createCalendarObject(
            $cid, 
            $veid, 
            "BEGIN:VCALENDAR\nVERSION:2.0\n" . $ve->serialize() . "\nEND:VCALENDAR"
        );
        // return event object or null
        if (isset($rs)) {
            $eo->ID = $veid;
            $eo->CID = $cid;
            $eo->State = trim($rs,'"');
            return $eo;
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
     * @param EventObject $eo - Source Data
	 * 
	 * @return EventObject
	 */
	public function updateCollectionItem(string $cid, string $iid, EventObject $so): ?EventObject {

        // check for id - must contain id to update
        if (!empty($iid)) {
            // clone source object
            $eo = clone $so;
            // deposit attachment(s)
            if (count($eo->Attachments) > 0) {
                // create or update attachements in local data store
                $eo->Attachments = $this->createCollectionItemAttachment(
                    $eo->StartsOn->format('Y-m-d H.i.s') . " " . \OCA\EWS\Utile\Sanitizer::folder($eo->Label, true, true), 
                    $eo->Attachments
                );
            }
            // convert event object to vevent object
            $ve = $this->fromEventObject($eo);
            // update item in data store
            $rs = $this->DataStore->updateCalendarObject(
                $cid, 
                $iid, 
                "BEGIN:VCALENDAR\nVERSION:2.0\n" . $ve->serialize() . "\nEND:VCALENDAR"
            );
        }
        // return event object or null
        if (isset($rs)) {
            $eo->ID = $iid;
            $eo->CID = $cid;
			$eo->State = trim($rs,'"');
            return $eo;
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
	 * @return EventAttachmentObject
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
                    $ao = new EventAttachmentObject('D');
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
     * @param array $batch - Collection of EventAttachmentObject(s) objects
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
                $fl = 'Calendar/' . $fn;
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
     * convert vevent object to event object
     * 
     * @since Release 1.0.0
     * 
	 * @param VEvent $vo - source object
	 * 
	 * @return EventObject converted object
	 */
	public function toEventObject(VEvent $vo): EventObject {
		
        // construct event object
		$eo = new EventObject();
        // Origin
		$eo->Origin = 'L';
        // UUID
        if (isset($vo->UID)) {
            $eo->UUID = trim($vo->UID->getValue());
        }
        // Creation Date
        if (isset($vo->CREATED)) {
            $eo->CreatedOn = new DateTime($vo->CREATED->getValue());
        }
        // Modification Date
        if (isset($vo->{'LAST-MODIFIED'})) {
            $eo->ModifiedOn = new DateTime($vo->{'LAST-MODIFIED'}->getValue());
        }
        // Starts Date/Time
        // Starts Time Zone
        if (isset($vo->DTSTART)) {
            if (isset($vo->DTSTART->parameters['TZID'])) {
                $eo->StartsTZ = new DateTimeZone($vo->DTSTART->parameters['TZID']->getValue());
            }
            elseif (str_contains($vo->DTSTART, 'Z')) {
                $eo->StartsTZ = new DateTimeZone('UTC');
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $eo->StartsTZ = $this->UserTimeZone;
            }
            else {
                $eo->StartsTZ = $this->DefaultTimeZone;
            }
            $eo->StartsOn = new DateTime($vo->DTSTART->getValue(), $eo->StartsTZ);
        }
        // Ends Date/Time
        // Ends Time Zone
        if (isset($vo->DTEND)) {
            if (isset($vo->DTEND->parameters['TZID'])) {
                $eo->EndsTZ = new DateTimeZone($vo->DTEND->parameters['TZID']->getValue());
            }
            elseif (str_contains($vo->DTSTART, 'Z')) {
                $eo->EndsTZ = new DateTimeZone('UTC');
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $eo->EndsTZ = $this->UserTimeZone;
            }
            else {
                $eo->EndsTZ = $this->DefaultTimeZone;
            }
            $eo->EndsOn = new DateTime($vo->DTEND->getValue(), $eo->EndsTZ);
        }
        // Label
        if (isset($vo->SUMMARY)) {
            $eo->Label = trim($vo->SUMMARY->getValue());
        }
        // Notes
        if (isset($vo->DESCRIPTION)) {
            if (!empty(trim($vo->DESCRIPTION->getValue()))) {
                $eo->Notes = trim($vo->DESCRIPTION->getValue());
            }
        }
        // Location
        if (isset($vo->LOCATION)) {
            $eo->Location = trim($vo->LOCATION->getValue());
        }
        // Availability
        if (isset($vo->TRANSP)) {
            if ($vo->TRANSP->getValue() == 'TRANSPARENT') {
                $eo->Availability = 'Free';
            }
            else {
                $eo->Availability = 'Busy';
            }
        }
        // Priority
        if (isset($vo->PRIORITY)) {
            $eo->Priority = trim($vo->PRIORITY->getValue());
        }
        // Sensitivity
        if (isset($vo->CLASS)) {
            $eo->Sensitivity = $this->fromClass($vo->CLASS->getValue());
        }
        // Color
        if (isset($vo->COLOR)) {
            $eo->Color = trim($vo->COLOR->getValue());
        }
        // Tag(s)
        if (isset($vo->CATEGORIES)) {
            foreach($vo->CATEGORIES->getParts() as $entry) {
                $eo->addTag(
                    trim($entry)
                );
            }
        }
        // Organizer
        if (isset($vo->ORGANIZER)) {
            // address
            $eo->Organizer->Address = str_replace('mailto:', "", $vo->ORGANIZER->getValue());
            // name
            if (isset($vo->ORGANIZER->parameters['CN'])) {
                $eo->Organizer->Name = $vo->ORGANIZER->parameters['CN']->getValue();
            }
        }
        // Attendee(s)
        if (isset($vo->ATTENDEE)) {
            foreach($vo->ATTENDEE as $entry) {
                // Attendee Type
                if (isset($entry->parameters['ROLE'])) {
                    $t = $this->fromAttendeeRole($entry->parameters['ROLE']->getValue());
                } else {
                    $t = $this->fromAttendeeRole(null);
                }
                // Attendee Name
                if (isset($entry->parameters['CN'])) {
                    $n = $entry->parameters['CN']->getValue();
                } else {
                    $n = null;
                }
                // Attendee Attendance
                if (isset($entry->parameters['PARTSTAT'])) {
                    $r = $this->fromAttendeeStatus($entry->parameters['PARTSTAT']->getValue());
                } else {
                    $r = $this->fromAttendeeStatus(null);
                }
                // construct entry
                $eo->addAttendee(
                    str_replace('mailto:', "", $entry->getValue()),
                    $n,
                    $t,
                    $r
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
                            $eo->addNotification(
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

                    $eo->addAttachment(
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
                $eo->Occurrence->Precision = $this->fromFrequency($parts['FREQ']);
            }
            if (isset($parts['INTERVAL'])) {
                $eo->Occurrence->Interval = $parts['INTERVAL'];
            }
            if (isset($parts['COUNT'])) {
                $eo->Occurrence->Iterations = $parts['COUNT'];
            }
            if (isset($parts['UNTIL'])) {
                $eo->Occurrence->Concludes = new DateTime($parts['UNTIL']);
            }
            if (isset($parts['BYDAY'])) {
                if (is_array($parts['BYDAY'])) {
                    $eo->Occurrence->OnDayOfWeek = $this->fromByDay($parts['BYDAY']);
                }
                else {
                    $eo->Occurrence->OnDayOfWeek = $this->fromByDay(array($parts['BYDAY']));
                }
            }
            if (isset($parts['BYMONTH'])) {
                if (is_array($parts['BYMONTH'])) {
                    $eo->Occurrence->OnMonthOfYear = $parts['BYMONTH'];
                }
                else {
                    $eo->Occurrence->OnMonthOfYear = array($parts['BYMONTH']);
                }
            }
            if (isset($parts['BYMONTHDAY'])) {
                if (is_array($parts['BYMONTHDAY'])) {
                    $eo->Occurrence->OnDayOfMonth = $parts['BYMONTHDAY'];
                }
                else {
                    $eo->Occurrence->OnDayOfMonth = array($parts['BYMONTHDAY']);
                }
            }
            if (isset($parts['BYYEARDAY'])) {
                if (is_array($parts['BYYEARDAY'])) {
                    $eo->Occurrence->OnDayOfYear = $parts['BYYEARDAY'];
                }
                else {
                    $eo->Occurrence->OnDayOfYear = array($parts['BYYEARDAY']);
                }
            }
            if (isset($parts['BYSETPOS'])) {
                $eo->Occurrence->Pattern = 'R';
                $eo->Occurrence->OnWeekOfMonth = array($parts['BYSETPOS']);
            } else {
                $eo->Occurrence->Pattern = 'A';
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
                    $eo->Occurrence->Excludes[] = new DateTime($entry->getValue(), $tz);
                }
            }
        }
        
		// return event object
		return $eo;
        
    }

    /**
     * Convert event object to vevent object
     * 
     * @since Release 1.0.0
     * 
	 * @param EventObject $vo - source object
	 * 
	 * @return VEvent converted object
	 */
    public function fromEventObject(EventObject $eo): VEvent{

        // construct vevent object
        $vo = new \Sabre\VObject\Component\VCalendar();
        $vo = $vo->createComponent('VEVENT');
        // UID
        if ($eo->UUID) {
            $vo->UID->setValue($eo->UUID);
        }
        // Starts Date/Time
        // Starts Time Zone
        if (isset($eo->StartsOn)) {
            $vo->add('DTSTART');

            if (isset($eo->StartsTZ)) {
                $tz = $eo->StartsTZ->getName();  
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $tz = $this->UserTimeZone->getName();
            }
            else {
                $tz = $this->DefaultTimeZone->getName();
            }

            $dt = clone $eo->StartsOn;
            $dt->setTimezone(new DateTimeZone($tz));
            $vo->DTSTART->setValue($dt->format('Ymd\THis'));
            $vo->DTSTART->add('TZID', $tz);
            unset($dt);
            unset($tz);
        }
        // Ends Date/Time
        // Ends Time Zone
        if (isset($eo->EndsOn)) {
            $vo->add('DTEND');

            if (isset($eo->EndsTZ)) {
                $tz = $eo->EndsTZ->getName();  
            }
            elseif ($this->UserTimeZone instanceof \DateTimeZone) {
                $tz = $this->UserTimeZone->getName();
            }
            else {
                $tz = $this->DefaultTimeZone->getName();
            }

            $dt = clone $eo->EndsOn;
            $dt->setTimezone(new DateTimeZone($tz));
            $vo->DTEND->setValue($dt->format('Ymd\THis'));
            $vo->DTEND->add('TZID', $tz);
            unset($dt);
            unset($tz);
        }
        // Time Zone
        if ($eo->TimeZone) {
            //$vo->add('TZ', $eo->TimeZone->getName());
        }
        // Label
        if ($eo->Label) {
            $vo->add('SUMMARY',$eo->Label);
        }
        // Notes
        if (isset($eo->Notes)) {
            $vo->add('DESCRIPTION', $eo->Notes);
        }
        // Location
        if (isset($eo->Location)) {
            $vo->add('LOCATION', trim($eo->Location));
        }
        // Availability
        if (isset($eo->Availability)) {
            if ($eo->Availability == 'Free') {
                $vo->add('TRANSP', 'TRANSPARENT');
            }
            else {
                $vo->add('TRANSP', 'OPAQUE');
            }
        }
        // Priority
        if (isset($eo->Priority)) {
            $vo->add('PRIORITY', $eo->Priority);
        }
        // Sensitivity
        if (isset($eo->Sensitivity)) {
            $vo->add('CLASS', $this->toClass($eo->Sensitivity));
        }
        // Color
        if (isset($eo->Color)) {
            $vo->add('COLOR', trim($eo->Color));
        }
        // Tag(s)
        if (count($eo->Tags) > 0) {
            $vo->add('CATEGORIES', $eo->Tags);
        }
        // Organizer
        if (isset($vo->Organizer) && isset($vo->Organizer->Address)) {
            $vo->add(
                'ORGANIZER', 
                'mailto:' . $vo->Organizer->Address,
                array('CN' => $vo->Organizer->Name)
            );
        }
        // Attendee(s)
        if (count($eo->Attendee) > 0) {
            foreach($eo->Attendee as $entry) {
                $p = array();
                // Attendee Type
                $p['ROLE'] = $this->toAttendeeRole($entry->Type);
                // Attendee Name
                if (isset($entry->Name)) {
                    $p['CN'] = $entry->Name;
                } else {
                    $p['CN'] = '';
                }
                // Attendee Attendance
                $p['PARTSTAT'] = $this->toAttendeeStatus($entry->Attendance);
                
                $vo->add('ATTENDEE', 'mailto:' . $entry->Address, $p);
                unset($p);
            }
        }
        // Attachment(s)
        if (count($eo->Attachments) > 0) {
            foreach($eo->Attachments as $entry) {
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
        if (count($eo->Notifications) > 0) {
            foreach($eo->Notifications as $entry) {
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
        if (isset($eo->Occurrence->Precision)) {
            $p = array();
            // Occurrence Precision
            if (isset($eo->Occurrence->Precision)) {
                $p['FREQ'] = $this->toFrequency($eo->Occurrence->Precision);
            }
            // Occurrence Interval
            if (isset($eo->Occurrence->Interval)) {
                $p['INTERVAL'] = $eo->Occurrence->Interval;
            }
            // Occurrence Interval
            if (isset($eo->Occurrence->Iterations)) {
                $p['COUNT'] = $eo->Occurrence->Iterations;
            }
            // Occurrence Conclusion
            if (isset($eo->Occurrence->Concludes)) {
                if ($eo->Origin == 'R') {
                    // adjust for how until day is calculated
                    $p['UNTIL'] = (clone $eo->Occurrence->Concludes)
                                  ->add(new DateInterval('PT24H'))->format('Ymd\THis\Z');
                }
                else {
                    $p['UNTIL'] = $eo->Occurrence->Concludes->format('Ymd\THis\Z');
                }
            }
            // Occurrence Day Of Week
            if (count($eo->Occurrence->OnDayOfWeek) > 0) {
                $p['BYDAY'] = $this->toByDay($eo->Occurrence->OnDayOfWeek);
            }
            // Occurrence Day Of Month
            if (count($eo->Occurrence->OnDayOfMonth) > 0) {
                $p['BYMONTHDAY'] = implode(',', $eo->Occurrence->OnDayOfMonth);
            }
            // Occurrence Day Of Year
            if (count($eo->Occurrence->OnDayOfYear) > 0) {
                $p['BYYEARDAY'] = implode(',', $eo->Occurrence->OnDayOfYear);
            }
            // Occurrence Month Of Year
            if (count($eo->Occurrence->OnMonthOfYear) > 0) {
                $p['BYMONTH'] = implode(',', $eo->Occurrence->OnMonthOfYear);
            }
            // Occurrence Relative
            if ($eo->Occurrence->Pattern == 'R') {
                $p['BYSETPOS'] = implode(',', $eo->Occurrence->OnWeekOfMonth);
            }
            // create attribute
            $vo->add('RRULE', $p);
            unset($p);
            // Occurrence Excludes
            if (count($eo->Occurrence->Excludes) > 0) {
                foreach ($eo->Occurrence->Excludes as $entry) {
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
     * convert local frequency to event object occurrence precision
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $frequency - local frequency value
	 * 
	 * @return int event object occurrence precision value
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
     * convert event object occurrence precision to local frequency
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $precision - event object occurrence precision value
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
     * convert local by day to event object days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - local by day values(s)
	 * 
	 * @return array event object days of the week values(s)
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
     * convert event object days of the week to local by day
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - event object days of the week values(s)
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
     * convert local class to event object sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - local class value
	 * 
	 * @return int|null event object sensitivity value
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
     * convert event object sensitivity to local class
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - event object sensitivity value
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
     * convert local attendee role to event object attendee attendance
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $role - local attendee role value
	 * 
	 * @return int event object attendee attendance value
	 */
    private function fromAttendeeRole(?string $role): string {
		
        // role conversion reference
		$_tm = array(
			'REQ-PARTICIPANT' => 'R',
			'OPT-PARTICIPANT' => 'O',
			'NON-PARTICIPANT' => 'N',
            'CHAIR' => 'C'
		);
        // evaluate if role value exists
		if (isset($_tm[$role])) {
			// return converted attendance value
			return $_tm[$role];
		} else {
            // return default attendance value
			return 'R';
		}
		
	}

    /**
     * convert event object attendee attendance to local attendee role
     *  
     * @since Release 1.0.0
     * 
	 * @param string $attendance - event object attendee attendance value
	 * 
	 * @return string local attendee role value
	 */
	private function toAttendeeRole(?string $attendance): string {

        // attendance conversion reference
		$_tm = array(
			'R' => 'REQ-PARTICIPANT',
			'O' => 'OPT-PARTICIPANT',
			'N' => 'NON-PARTICIPANT',
			'C' => 'CHAIR'
		);
        // evaluate if attendance value exists
		if (isset($_tm[$attendance])) {
			// return converted role value
			return $_tm[$attendance];
		} else {
            // return default role value
			return 'REQ-PARTICIPANT';
		}

	}

    /**
     * convert local attendee status to event object attendee status
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $status - local attendee status value
	 * 
	 * @return int event object attendee status value
	 */
    private function fromAttendeeStatus(?string $status): string {
		
        // status conversion reference
		$_tm = array(
			'ACCEPTED' => 'A',
			'DECLINED' => 'D',
			'TENTATIVE' => 'T',
            'DELEGATED' => 'R',
			'NEEDS-ACTION' => 'N'
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
     * convert event object attendee status to local attendee status
     *  
     * @since Release 1.0.0
     * 
	 * @param string $status - event object attendee status value
	 * 
	 * @return string local attendee status value
	 */
	private function toAttendeeStatus(?string $status): string {

        // status conversion reference
		$_tm = array(
			'A' => 'ACCEPTED',
			'D' => 'DECLINED',
			'T' => 'TENTATIVE',
			'R' => 'DELEGATED',
			'N' => 'NEEDS-ACTION'
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
     * convert local alarm action to event object alarm action type
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $action - local alarm action value
	 * 
	 * @return int event object alarm action type value
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
     * convert event object alarm type to local alram action
     *  
     * @since Release 1.0.0
     * 
	 * @param string $type - event object action type value
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
     * convert local duration period to event object date interval
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $period - local duration period value
	 * 
	 * @return DateInterval event object date interval object
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
     * convert event object date interval to local duration period
	 * 
     * @since Release 1.0.0
     * 
	 * @param DateInterval $period - event object date interval object
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
