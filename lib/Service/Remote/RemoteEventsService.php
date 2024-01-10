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

namespace OCA\EWS\Service\Remote;

use Datetime;
use DateTimeZone;
use DateInterval;
use Psr\Log\LoggerInterface;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Service\Remote\RemoteCommonService;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Components\EWS\Type\CalendarItemType;
use OCA\EWS\Objects\EventCollectionObject;
use OCA\EWS\Objects\EventObject;
use OCA\EWS\Objects\EventAttachmentObject;

class RemoteEventsService {
	/**
	 * @var LoggerInterface
	 */
	protected $logger;
	/**
	 * @var RemoteCommonService
	 */
	protected $RemoteCommonService;
	/**
	 * @var EWSClient
	 */
	protected ?EWSClient $DataStore = null;
	/**
	 * @var Object
	 */
	protected $Configuration;
	/**
	 * @var DateTimeZone
	 */
	protected ?DateTimeZone $SystemTimeZone = null;
    /**
	 * @var DateTimeZone
	 */
	protected ?DateTimeZone $UserTimeZone = null;
	/**
	 * @var Object
	 */
	protected ?object $DefaultCollectionProperties = null;
	/**
	 * @var Object
	 */
	protected ?object $DefaultItemProperties = null;
	
	public function __construct (string $appName,
								LoggerInterface $logger,
								RemoteCommonService $RemoteCommonService) {
		$this->logger = $logger;
		$this->RemoteCommonService = $RemoteCommonService;
	}

	public function configure($configuration, EWSClient $DataStore) : void {
		
		// assign configuration
		$this->Configuration = $configuration;
		// assign remote data store
		$this->DataStore = $DataStore;
		// assign timezones
		$this->SystemTimeZone = $configuration->SystemTimeZone;
		$this->UserTimeZone = $configuration->UserTimeZone;
		
	}

	/**
	 * retrieve list of collections in remote storage
     * 
     * @since Release 1.0.0
	 * 
	 * @param string $source		folder source (U - User Folders, P - Public Folders)
	 * @param string $prefixName	string to append to folder name
	 *
	 * @return array of collections and properties
	 */
	public function listCollections(string $source = 'U', string $prefixName = ''): array {

		// execute command
		$cr = $this->RemoteCommonService->fetchFoldersByType($this->DataStore, 'IPF.Appointment', 'I', $this->constructDefaultCollectionProperties(), $source);
		// process response
		$cl = array();
		if (isset($cr)) {
			foreach ($cr->CalendarFolder as $folder) {
				$cl[] = array('id'=>$folder->FolderId->Id, 'name'=>$prefixName . $folder->DisplayName,'count'=>$folder->TotalCount);
			}
		}
		// return collections
		return $cl;

	}

	/**
	 * retrieve properties for specific collection
     * 
     * @since Release 1.0.0
	 * 
	 * @param string $cid - Collection Id
	 * 
	 * @return EventCollectionObject
	 */
	public function fetchCollection(string $cid): ?EventCollectionObject {

        // execute command
		$cr = $this->RemoteCommonService->fetchFolder($this->DataStore, $cid, false, 'I', $this->constructDefaultCollectionProperties());
		// process response
		if (isset($cr) && (count($cr->CalendarFolder) > 0)) {
		    $ec = new EventCollectionObject(
				$cr->CalendarFolder[0]->FolderId->Id,
				$cr->CalendarFolder[0]->DisplayName,
				$cr->CalendarFolder[0]->FolderId->ChangeKey,
				$cr->CalendarFolder[0]->TotalCount
			);
			if (isset($cr->CalendarFolder[0]->ParentFolderId->Id)) {
				$ec->AffiliationId = $cr->CalendarFolder[0]->ParentFolderId->Id;
			}
			return $ec;
		} else {
			return null;
		}

    }
	
	/**
     * create collection in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection Item ID
	 * 
	 * @return EventCollectionObject
	 */
	public function createCollection(string $cid, string $name, bool $ctype = false): ?EventCollectionObject {
        
		// construct command object
		$ec = new \OCA\EWS\Components\EWS\Type\CalendarFolderType();
		$ec->DisplayName = $name;
		// execute command
		$cr = $this->RemoteCommonService->createFolder($this->DataStore, $cid, $ec, $ctype);
        // process response
		if (isset($cr) && (count($cr->CalendarFolder) > 0)) {
		    return new EventCollectionObject(
				$cr->CalendarFolder[0]->FolderId->Id,
				$name,
				$cr->CalendarFolder[0]->FolderId->ChangeKey
			);
		} else {
			return null;
		}

    }

	/**
     * delete collection in remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $cid - Collection ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollection(string $cid): bool {
        
		// construct command object
        $ec = new \OCA\EWS\Components\EWS\Type\FolderIdType($cid);
		// execute command
        $cr = $this->RemoteCommonService->deleteFolder($this->DataStore, array($ec));
		// process response
        if ($cr) {
            return true;
        } else {
            return false;
        }

    }

	/**
	 * retrieve alteration for specific collection
     * 
     * @since Release 1.0.0
	 * 
	 * @param string $cid - Collection Id
	 * @param string $state - Collection State (Initial/Last)
	 * 
	 * @return object
	 */
	public function fetchCollectionChanges(string $cid, string $state, string $scheme = 'I'): ?object {

        // execute command
        $cr = $this->RemoteCommonService->fetchFolderChanges($this->DataStore, $cid, $state, false, 512, $scheme);
		// return response
		return $cr;

    }

	/**
     * retrieve all collection items uuids from remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
	 * 
	 * @return array
	 */
	public function fetchCollectionItemsUUID(string $cid, bool $ctype = false): array {

		// construct properties required
		$properties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		$properties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:UID');
		$properties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
        // define place holders
        $data = array();
        $offset = 0;
        do {
            // execute command
            $ro = $this->RemoteCommonService->fetchItems($this->DataStore, $cid, $ctype, $offset, 512, 'I', $properties);
            // validate response object
            if (isset($ro) && count($ro->CalendarItem) > 0) {
                foreach ($ro->CalendarItem as $entry) {
					// extract and validate UUID from standard properties
					$uuid = $this->fromUID($entry->UID);
					// evaluate if valid uuid was not found
					if (empty($uuid)) {
						// extract and validate UUID from extended properties
						$uuid = $this->fromUID($entry->ExtendedProperty[0]->Value);
					}
					// evaluate if valid uuid exists
                    if (!empty($uuid)) {
						// add item id and uuid to id collection
                        $data[] = array('ID'=>$entry->ItemId->Id, 'UUID'=>$uuid);
                    }
                }
				// increment offset by count of returned items
                $offset += count($ro->CalendarItem);
            }
        }
        while (isset($ro) && count($ro->CalendarItem) > 0);
        // return id collection
		return $data;
    }

	/**
     * retrieve collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $iid - Collection Item ID
	 * 
	 * @return ContactObject
	 */
	public function fetchCollectionItem(string $iid): ?EventObject {
        
		// construct identification object
        $io = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);
		// execute command
		$ro = $this->RemoteCommonService->fetchItem($this->DataStore, array($io), 'D', $this->constructDefaultItemProperties());
        // validate response
		if (isset($ro->CalendarItem)) {
			// convert to event object
            $eo = $this->toEventObject($ro->CalendarItem[0]);
            // retrieve attachment(s) from remote data store
			if (count($eo->Attachments) > 0) {
				$eo->Attachments = $this->fetchCollectionItemAttachment(array_column($eo->Attachments, 'Id'));
			}
            // return object
		    return $eo;
		} else {
			return null;
		}

    }

	/**
     * find collection item by uuid in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $uuid -Collection Item UUID
	 * 
	 * @return EventObject
	 */
	public function fetchCollectionItemByUUID(string $cid, string $uuid): ?EventObject {

        // retrieve properties for a specific collection item
		$ro = $this->RemoteCommonService->findItemByUUID($this->DataStore, $cid, $uuid, false, 'D', $this->constructDefaultItemProperties());
		// validate response
		if (isset($ro->CalendarItem)) {
			// convert to event object
            $eo = $this->toEventObject($ro->CalendarItem[0]);
            // retrieve attachment(s) from remote data store
			if (count($eo->Attachments) > 0) {
				$eo->Attachments = $this->fetchCollectionItemAttachment(array_column($eo->Attachments, 'Id'));
			}
            // return object
		    return $eo;
		} else {
			return null;
		}
		
    }

	/**
     * create collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param EventObject $so - Source Data
	 * 
	 * @return EventObject
	 */
	public function createCollectionItem(string $cid, EventObject $so): ?EventObject {

        // construct request object
        $ro = new CalendarItemType();
		// UUID
		if (!empty($so->UUID)) {
            $ro->UID = $so->UUID;
			$ro->ExtendedProperty[] = $this->createFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UUID);
        }
		// Start Date/Time
        if (!empty($so->StartsOn)) {
			// ews wants the date time in UTC
			// clone start date
			$dt = clone $so->StartsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct start time attribute
			$ro->Start = $dt->format('Y-m-d\\TH:i:s\Z');
			// evaluate if event starts time zone is present
			if ($so->StartsTZ instanceof \DateTimeZone) {
				$tz = $so->StartsTZ;
			}
			// evaluate if user default time zone is present
			elseif ($this->UserTimeZone instanceof \DateTimeZone) {
				$tz = $this->UserTimeZone;
			}
			// use system default time zone if no other option was present
			else {
				$tz = $this->SystemTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if (!empty($tz)) {
				$ro->StartTimeZone = $this->constructTimeZone($tz);
			} else {
				$ro->StartTimeZone = $this->constructTimeZone('UTC');
			}
			unset($tz);
			unset($dt);
        }
		// End Date/Time
		if (!empty($so->EndsOn)) {
			// ews wants the date time in UTC
			// clone end date
			$dt = clone $so->EndsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time attribute
			$ro->End = $dt->format('Y-m-d\\TH:i:s\Z');
			// evaluate if event ends time zone is present
			if ($so->EndsTZ instanceof \DateTimeZone) {
				$tz = $so->EndsTZ;
			}
			// evaluate if user default time zone is present
			elseif ($this->UserTimeZone instanceof \DateTimeZone) {
				$tz = $this->UserTimeZone;
			}
			// use system default time zone if no other option was present
			else {
				$tz = $this->SystemTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if (!empty($tz)) {
				$ro->EndTimeZone = $this->constructTimeZone($tz);
			} else {
				$ro->EndTimeZone = $this->constructTimeZone('UTC');
			}
			unset($tz);
			unset($dt);
		}
		// All Day Event
		if(!empty($so->Span) && $so->Span == 'F') {
			$ro->IsAllDayEvent = true;
		}
		else {
			$ro->IsAllDayEvent = false;
		}
		// TimeZone
		if ($so->TimeZone instanceof \DateTimeZone) {
			// convert time zone
			$tz = $this->toTimeZone($so->TimeZone);
			if (isset($tz)) {
				$ro->TimeZone = $this->constructTimeZone($tz);
			}
		}
		// Label
        if (!empty($so->Label)) {
            $ro->Subject = $so->Label;
        }
		// Notes
		if (!empty($so->Notes)) {
			$ro->Body = new \OCA\EWS\Components\EWS\Type\BodyType(
				'Text',
				$so->Notes
			);
		}
		// Location
		if (!empty($so->Location)) {
			$ro->Location = $so->Location;
		}
		// Availability
		if (!empty($so->Availability)) {
			$ro->LegacyFreeBusyStatus = $so->Availability;
		}
		// Priority
		if (!empty($so->Priority)) {
			$ro->Importance = $this->toImportance($so->Priority);
		}
		// Sensitivity
		if (!empty($so->Sensitivity)) {
			$ro->Sensitivity = $this->toSensitivity($so->Sensitivity);
		}
		// Tag(s)
		if (count($so->Tags) > 0) {
			$ro->Categories = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType;
			foreach ($so->Tags as $entry) {
				$ro->Categories->String[] = $entry;
			}
		}
		// Attendee(s)
		if (count($so->Attendee) > 0) {
            foreach ($so->Attendee as $entry) {
				if ($entry->Type == 'O') {
					if (!isset($ro->OptionalAttendees)) {$ro->OptionalAttendees = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType;}
						$ro->OptionalAttendees->Attendee[] = new \OCA\EWS\Components\EWS\Type\AttendeeType(
						new \OCA\EWS\Components\EWS\Type\EmailAddressType(
							$entry->Address,
							$entry->Name
						),
						$this->toAttendeeResponse($entry->Attendance)
					);
				}
				else {
					if (!isset($ro->RequiredAttendees)) {$ro->RequiredAttendees = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType;}
						$ro->RequiredAttendees->Attendee[] = new \OCA\EWS\Components\EWS\Type\AttendeeType(
						new \OCA\EWS\Components\EWS\Type\EmailAddressType(
							$entry->Address,
							$entry->Name
						),
						$this->toAttendeeResponse($entry->Attendance)
					);
				}
            }
        }
		// Notifications
		if (count($so->Notifications) > 0) {
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil((($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp()) / 60));
				$ro->ReminderIsSet = true;
				$ro->ReminderMinutesBeforeStart = $t;
				unset($t);
			}
			elseif ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'R') {
				$w = clone $so->Notifications[0]->When;
				$w->invert = 0;
				$t = ceil((new DateTime('@0'))->add($w)->getTimestamp() / 60);
				$ro->ReminderIsSet = true;
				$ro->ReminderMinutesBeforeStart = $t;
				unset($w, $t);
			}
		}
		// Occurrence
		if (isset($so->Occurrence) && !empty($so->Occurrence->Precision)) {

			$ro->Recurrence = new \OCA\EWS\Components\EWS\Type\RecurrenceType();

			// Occurrence Iterations
			if (!empty($so->Occurrence->Iterations)) {
				$ro->Recurrence->NumberedRecurrence = new \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType();
				$ro->Recurrence->NumberedRecurrence->NumberOfOccurrences = $so->Occurrence->Iterations;
				$ro->Recurrence->NumberedRecurrence->StartDate = $so->StartsOn->format('Y-m-d');
			}
			// Occurrence Conclusion
			if (!empty($so->Occurrence->Concludes)) {
				$ro->Recurrence->EndDateRecurrence = new \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType();
				$ro->Recurrence->EndDateRecurrence->StartDate = $so->StartsOn->format('Y-m-d');
				if ($so->Origin == 'L') {
					// subtract 1 day to adjust in how the end date is calculated in NC and EWS
					$ro->Recurrence->EndDateRecurrence->EndDate = date_modify(clone $so->Occurrence->Concludes, '-1 day')->format('Y-m-d');
				}
				else {
					$ro->Recurrence->EndDateRecurrence->EndDate = $so->Occurrence->Concludes->format('Y-m-d');
				}
			}
			// No Iterations And No Conclusion Date
			if (empty($so->Occurrence->Iterations) && empty($so->Occurrence->Concludes)) {
				$ro->Recurrence->NoEndRecurrence = new \OCA\EWS\Components\EWS\Type\NoEndRecurrenceRangeType();
			}

			// Based on Precision
			// Occurrence Daily
			if ($so->Occurrence->Precision == 'D') {
				$ro->Recurrence->DailyRecurrence = new \OCA\EWS\Components\EWS\Type\DailyRecurrencePatternType();
				if (!empty($so->Occurrence->Interval)) {
					$ro->Recurrence->DailyRecurrence->Interval = $so->Occurrence->Interval;
				}
				else {
					$ro->Recurrence->DailyRecurrence->Interval = '1';
				}
			}
			// Occurrence Weekly
			elseif ($so->Occurrence->Precision == 'W') {
				$ro->Recurrence->WeeklyRecurrence = new \OCA\EWS\Components\EWS\Type\WeeklyRecurrencePatternType();
				if (!empty($so->Occurrence->Interval)) {
					$ro->Recurrence->WeeklyRecurrence->Interval = $so->Occurrence->Interval;
				}
				else {
					$ro->Recurrence->WeeklyRecurrence->Interval = '1';
				}
				$ro->Recurrence->WeeklyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek);
				$ro->Recurrence->WeeklyRecurrence->FirstDayOfWeek = 'Monday';
			}
			// Occurrence Monthly
			elseif ($so->Occurrence->Precision == 'M') {
				if ($so->Occurrence->Pattern == 'A') {
					$ro->Recurrence->AbsoluteMonthlyRecurrence = new \OCA\EWS\Components\EWS\Type\AbsoluteMonthlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$ro->Recurrence->AbsoluteMonthlyRecurrence->Interval = $so->Occurrence->Interval;
					}
					else {
						$ro->Recurrence->AbsoluteMonthlyRecurrence->Interval = '1';
					}
					$ro->Recurrence->AbsoluteMonthlyRecurrence->DayOfMonth = $this->toDaysOfMonth($so->Occurrence->OnDayOfMonth);
				}
				elseif ($so->Occurrence->Pattern == 'R') {
					$ro->Recurrence->RelativeMonthlyRecurrence = new \OCA\EWS\Components\EWS\Type\RelativeMonthlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$ro->Recurrence->RelativeMonthlyRecurrence->Interval = $so->Occurrence->Interval;	
					}
					else {
						$ro->Recurrence->RelativeMonthlyRecurrence->Interval = '1';
					}
					if (count($so->Occurrence->OnDayOfWeek) > 0) {
						$ro->Recurrence->RelativeMonthlyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek, true);
					}
					if (count($so->Occurrence->OnWeekOfMonth) > 0) {
						$ro->Recurrence->RelativeMonthlyRecurrence->DayOfWeekIndex = $this->toWeekOfMonth($so->Occurrence->OnWeekOfMonth);
					}
				}


			}
			// Occurrence Yearly
			elseif ($so->Occurrence->Precision == 'Y') {
				if ($so->Occurrence->Pattern == 'A') {
					$ro->Recurrence->AbsoluteYearlyRecurrence = new \OCA\EWS\Components\EWS\Type\AbsoluteYearlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$ro->Recurrence->AbsoluteYearlyRecurrence->Interval = $so->Occurrence->Interval;
					}
					else {
						$ro->Recurrence->AbsoluteYearlyRecurrence->Interval = '1';
					}
					$ro->Recurrence->AbsoluteYearlyRecurrence->Month = $this->toMonthOfYear($so->Occurrence->OnMonthOfYear);
					$ro->Recurrence->AbsoluteYearlyRecurrence->DayOfMonth = $this->toDaysOfMonth($so->Occurrence->OnDayOfMonth);
				}
				elseif ($so->Occurrence->Pattern == 'R') {
					$ro->Recurrence->RelativeYearlyRecurrence = new \OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$ro->Recurrence->RelativeYearlyRecurrence->Interval = $so->Occurrence->Interval;	
					}
					else {
						$ro->Recurrence->RelativeYearlyRecurrence->Interval = '1';
					}
					if (count($so->Occurrence->OnDayOfWeek) > 0) {
						$ro->Recurrence->RelativeYearlyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek, true);
					}
					if (count($so->Occurrence->OnWeekOfMonth) > 0) {
						$ro->Recurrence->RelativeYearlyRecurrence->DayOfWeekIndex = $this->toWeekOfMonth($so->Occurrence->OnWeekOfMonth);
					}
					if (count($so->Occurrence->OnMonthOfYear) > 0) {
						$ro->Recurrence->RelativeYearlyRecurrence->Month = $this->toMonthOfYear($so->Occurrence->OnMonthOfYear);
					}
				}
			}
			// Occurrence Exclusions
			if (count($so->Occurrence->Excludes) > 0) {
				$ro->DeletedOccurrences = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfDeletedOccurrencesType();
				foreach ($so->Occurrence->Excludes as $entry) {
					// clone start date
					$dt = clone $entry;
					// change timezone on cloned date
					$dt->setTimezone(new DateTimeZone('UTC'));
					// construct start time property
					$ro->DeletedOccurrence[] = new \OCA\EWS\Components\EWS\Type\DeletedOccurrenceInfoType(
						$dt->format('Y-m-d\\TH:i:s\Z')
					);
					unset($dt);
				}
			}
		}
		
		// execute command
        $rs = $this->RemoteCommonService->createItem($this->DataStore, $cid, $ro);
        // process response
        if ($rs->CalendarItem[0]) {
			$eo = clone $so;
			$eo->ID = $rs->CalendarItem[0]->ItemId->Id;
			$eo->CID = $cid;
			$eo->State = $rs->CalendarItem[0]->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($eo->Attachments) > 0) {
				// create attachments in remote data store
				$eo->Attachments = $this->createCollectionItemAttachment($eo->ID, $eo->Attachments);
				$eo->State = $eo->Attachments[0]->AffiliateState;
			}
            return $eo;
        } else {
            return null;
        }

    }

	/**
     * update collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
	 * @param string $istate - Collection Item State
     * @param EventObject $so - Source Data
	 * 
	 * @return EventObject
	 */
	public function updateCollectionItem(string $cid, string $iid, string $istate, EventObject $so): ?EventObject {

        // request modifications array
        $rm = array();
        // request deletions array
        $rd = array();
		// UUID
        if (!empty($so->UUID)) {
			$rm[] = $this->updateFieldUnindexed('calendar:UID', 'UID', $so->UUID);
            $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UUID);
        }
        else {
			$rd[] = $this->deleteFieldUnindexed('calendar:UID');
            $rd[] = $this->deleteFieldExtendedByName('PublicStrings', 'DAV:uid', 'String');
        }
		// Time Zone
		if ($so->TimeZone instanceof \DateTimeZone) {
			$tz = $this->toTimeZone($so->TimeZone);
			$rm[] = $this->updateFieldUnindexed('calendar:TimeZone', 'TimeZone', $tz);
			unset($tz);
		}
        // Starts On
        if (!empty($so->StartsOn)) {
			// clone start date
			$dt = clone $so->StartsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct start time attribute
			$rm[] = $this->updateFieldUnindexed('calendar:Start', 'Start', $dt->format('Y-m-d\\TH:i:s\Z'));
			// evaluate if event starts time zone is present
			if ($so->StartsTZ instanceof \DateTimeZone) {
				$tz = $so->StartsTZ;
			}
			// evaluate if user default time zone is present
			elseif ($this->UserTimeZone instanceof \DateTimeZone) {
				$tz = $this->UserTimeZone;
			}
			// use system default time zone if no other option was present
			else {
				$tz = $this->SystemTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if (!empty($tz)) {
				$rm[] = $this->updateFieldUnindexed(
					'calendar:StartTimeZone',
					'StartTimeZone',
					$this->constructTimeZone($tz)
				);
			} else {
				$rm[] = $this->updateFieldUnindexed(
					'calendar:StartTimeZone',
					'StartTimeZone',
					$this->constructTimeZone('UTC')
				);
			}
			unset($tz);
			unset($dt);
        }
		// Ends On
        if (!empty($so->EndsOn)) {
			// clone end date
			$dt = clone $so->EndsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time property
			$rm[] = $this->updateFieldUnindexed('calendar:End', 'End', $dt->format('Y-m-d\\TH:i:s\Z'));
			// evaluate if event ends time zone is present
			if ($so->EndsTZ instanceof \DateTimeZone) {
				$tz = $so->EndsTZ;
			}
			// evaluate if user default time zone is present
			elseif ($this->UserTimeZone instanceof \DateTimeZone) {
				$tz = $this->UserTimeZone;
			}
			// use system default time zone if no other option was present
			else {
				$tz = $this->SystemTimeZone;
			}
			// construct start time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if (!empty($tz)) {
				$rm[] = $this->updateFieldUnindexed(
					'calendar:EndTimeZone',
					'EndTimeZone',
					$this->constructTimeZone($tz)
				);
			} else {
				$rm[] = $this->updateFieldUnindexed(
					'calendar:EndTimeZone',
					'EndTimeZone',
					$this->constructTimeZone('UTC')
				);
			}
			unset($tz);
			unset($dt);
        }
		// All Day Event
		if(!empty($so->Span) && $so->Span == 'F') {
			$rm[] = $this->updateFieldUnindexed('calendar:IsAllDayEvent', 'IsAllDayEvent', true);
		}
		else {
			$rm[] = $this->updateFieldUnindexed('calendar:IsAllDayEvent', 'IsAllDayEvent', false);
		}
		// Label
        if (!empty($so->Label)) {
            $rm[] = $this->updateFieldUnindexed('item:Subject', 'Subject', $so->Label);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('item:Subject');
        }
		// Notes
        if (!empty($so->Notes)) {
            $rm[] = $this->updateFieldUnindexed(
                'item:Body',
                'Body', 
                new \OCA\EWS\Components\EWS\Type\BodyType(
                    'HTML',
                    $so->Notes
            ));
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('item:Body');
        }
		// Location
		if (!empty($so->Location)) {
			$rm[] = $this->updateFieldUnindexed('calendar:Location', 'Location', $so->Location);
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:Location');
		}
		// Availability
		if (!empty($so->Availability)) {
			$rm[] = $this->updateFieldUnindexed('calendar:LegacyFreeBusyStatus', 'LegacyFreeBusyStatus', $so->Availability);
		}
		// Priority
		if (!empty($so->Priority)) {
			$rm[] = $this->updateFieldUnindexed('item:Importance', 'Importance', $this->toImportance($so->Priority));
		}
		// Sensitivity
		if (!empty($so->Sensitivity)) {
			$rm[] = $this->updateFieldUnindexed('item:Sensitivity', 'Sensitivity', $this->toSensitivity($so->Sensitivity));
		}
		// Tag(s)
		if (count($so->Tags) > 0) {
			$t = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType;
			foreach ($so->Tags as $entry) {
				$t->String[] = $entry;
			}
			$rm[] = $this->updateFieldUnindexed('item:Categories', 'Categories', $t);
			unset($t);
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('item:Categories');
		}
		// Attendee(s)
		if (count($so->Attendee) > 0) {
			foreach ($so->Attendee as $entry) {
				if ($entry->Type == 'O') {
					if (!isset($oa)) {$oa = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType;}
						$oa->Attendee[] = new \OCA\EWS\Components\EWS\Type\AttendeeType(
						new \OCA\EWS\Components\EWS\Type\EmailAddressType(
							$entry->Address,
							$entry->Name
						),
						$this->toAttendeeResponse($entry->Attendance)
					);
				}
				else {
					if (!isset($ra)) {$ra = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType;}
						$ra->Attendee[] = new \OCA\EWS\Components\EWS\Type\AttendeeType(
						new \OCA\EWS\Components\EWS\Type\EmailAddressType(
							$entry->Address,
							$entry->Name
						),
						$this->toAttendeeResponse($entry->Attendance)
					);
				}
			}
			if (isset($ra)) {
				$rm[] = $this->updateFieldUnindexed('calendar:RequiredAttendees', 'RequiredAttendees', $ra);
				unset($ra);
			}
			else {
				$rd[] = $this->deleteFieldUnindexed('calendar:RequiredAttendees');
			}
			if (isset($oa)) {
				$rm[] = $this->updateFieldUnindexed('calendar:OptionalAttendees', 'OptionalAttendees', $oa);
				unset($oa);
			}
			else {
				$rd[] = $this->deleteFieldUnindexed('calendar:OptionalAttendees');
			}
			unset($ra);
			unset($oa);
        }
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:RequiredAttendees');
			$rd[] = $this->deleteFieldUnindexed('calendar:OptionalAttendees');
		}
		// Notification(s)
		if (count($so->Notifications) > 0) {
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil((($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp()) / 60));
				$rm[] = $this->updateFieldUnindexed('item:ReminderMinutesBeforeStart', 'ReminderMinutesBeforeStart', $t);
				$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', true);
				unset($t);
			}
			elseif ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'R') {
				$w = clone $so->Notifications[0]->When;
				$w->invert = 0;
				$t = ceil((new DateTime('@0'))->add($w)->getTimestamp() / 60);
				$rm[] = $this->updateFieldUnindexed('item:ReminderMinutesBeforeStart', 'ReminderMinutesBeforeStart', $t);
				$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', true);
				unset($w, $t);
			}
		}
		else {
			$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', false);
			$rm[] = $this->updateFieldUnindexed('item:ReminderMinutesBeforeStart', 'ReminderMinutesBeforeStart', 0);
		}
		// Occurrence
		if (isset($so->Occurrence) && !empty($so->Occurrence->Precision)) {
			// construct recurrence object
			$f = new \OCA\EWS\Components\EWS\Type\RecurrenceType();
			// Iterations
			if (!empty($so->Occurrence->Iterations)) {
				$f->NumberedRecurrence = new \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType();
				$f->NumberedRecurrence->NumberOfOccurrences = $so->Occurrence->Iterations;
				$f->NumberedRecurrence->StartDate = $so->StartsOn->format('Y-m-d');
			}
			// Conclusion
			if (!empty($so->Occurrence->Concludes)) {
				$f->EndDateRecurrence = new \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType();
				$f->EndDateRecurrence->StartDate = $so->StartsOn->format('Y-m-d');
				$f->EndDateRecurrence->EndDate = $so->Occurrence->Concludes->format('Y-m-d');
			}
			// No Iterations And No Conclusion Date
			if (empty($so->Occurrence->Iterations) && empty($so->Occurrence->Concludes)) {
				$f->NoEndRecurrence = new \OCA\EWS\Components\EWS\Type\NoEndRecurrenceRangeType();
			}

			// Based on Precision
			// Daily Event
			if ($so->Occurrence->Precision == 'D') {
				$f->DailyRecurrence = new \OCA\EWS\Components\EWS\Type\DailyRecurrencePatternType();
				if (!empty($so->Occurrence->Interval)) {
					$f->DailyRecurrence->Interval = $so->Occurrence->Interval;
				}
				else {
					$f->DailyRecurrence->Interval = '1';
				}
			}
			// Weekly Event
			elseif ($so->Occurrence->Precision == 'W') {
				$f->WeeklyRecurrence = new \OCA\EWS\Components\EWS\Type\WeeklyRecurrencePatternType();
				if (!empty($so->Occurrence->Interval)) {
					$f->WeeklyRecurrence->Interval = $so->Occurrence->Interval;
				}
				else {
					$f->WeeklyRecurrence->Interval = '1';
				}
				$f->WeeklyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek);
				$f->WeeklyRecurrence->FirstDayOfWeek = 'Monday';
			}
			// Monthly Event
			elseif ($so->Occurrence->Precision == 'M') {
				if ($so->Occurrence->Pattern == 'A') {
					$f->AbsoluteMonthlyRecurrence = new \OCA\EWS\Components\EWS\Type\AbsoluteMonthlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$f->AbsoluteMonthlyRecurrence->Interval = $so->Occurrence->Interval;
					}
					else {
						$f->AbsoluteMonthlyRecurrence->Interval = '1';
					}
					$f->AbsoluteMonthlyRecurrence->DayOfMonth = $this->toDaysOfMonth($so->Occurrence->OnDayOfMonth);
				}
				elseif ($so->Occurrence->Pattern == 'R') {
					$f->RelativeMonthlyRecurrence = new \OCA\EWS\Components\EWS\Type\RelativeMonthlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$f->RelativeMonthlyRecurrence->Interval = $so->Occurrence->Interval;	
					}
					else {
						$f->RelativeMonthlyRecurrence->Interval = '1';
					}
					if (count($so->Occurrence->OnDayOfWeek) > 0) {
						$f->RelativeMonthlyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek, true);
					}
					if (count($so->Occurrence->OnWeekOfMonth) > 0) {
						$f->RelativeMonthlyRecurrence->DayOfWeekIndex = $this->toWeekOfMonth($so->Occurrence->OnWeekOfMonth);
					}
				}
			}
			// Yearly Event
			elseif ($so->Occurrence->Precision == 'Y') {
				if ($so->Occurrence->Pattern == 'A') {
					$f->AbsoluteYearlyRecurrence = new \OCA\EWS\Components\EWS\Type\AbsoluteYearlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$f->AbsoluteYearlyRecurrence->Interval = $so->Occurrence->Interval;
					}
					else {
						$f->AbsoluteYearlyRecurrence->Interval = '1';
					}
					$f->AbsoluteYearlyRecurrence->Month = $this->toMonthOfYear($so->Occurrence->OnMonthOfYear);
					$f->AbsoluteYearlyRecurrence->DayOfMonth = $this->toDaysOfMonth($so->Occurrence->OnDayOfMonth);
				}
				elseif ($so->Occurrence->Pattern == 'R') {
					$f->RelativeYearlyRecurrence = new \OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType();
					if (!empty($so->Occurrence->Interval)) {
						$f->RelativeYearlyRecurrence->Interval = $so->Occurrence->Interval;	
					}
					else {
						$f->RelativeYearlyRecurrence->Interval = '1';
					}
					if (count($so->Occurrence->OnDayOfWeek) > 0) {
						$f->RelativeYearlyRecurrence->DaysOfWeek = $this->toDaysOfWeek($so->Occurrence->OnDayOfWeek, true);
					}
					if (count($so->Occurrence->OnWeekOfMonth) > 0) {
						$f->RelativeYearlyRecurrence->DayOfWeekIndex = $this->toWeekOfMonth($so->Occurrence->OnWeekOfMonth);
					}
					if (count($so->Occurrence->OnMonthOfYear) > 0) {
						$f->RelativeYearlyRecurrence->Month = $this->toMonthOfYear($so->Occurrence->OnMonthOfYear);
					}
				}
			}
			
			$rm[] = $this->updateFieldUnindexed('calendar:Recurrence', 'Recurrence', $f);

			// Occurrence Exclusions
			if (count($so->Occurrence->Excludes) > 0) {
				$f = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfDeletedOccurrencesType();
				foreach ($so->Occurrence->Excludes as $entry) {
					// clone start date
					$dt = clone $entry;
					// change timezone on cloned date
					$dt->setTimezone(new DateTimeZone('UTC'));
					// construct start time property
					$f->DeletedOccurrence[] = new \OCA\EWS\Components\EWS\Type\DeletedOccurrenceInfoType(
						$dt->format('Y-m-d\\TH:i:s\Z')
					);
					unset($dt);
				}
				$rm[] = $this->updateFieldUnindexed('calendar:DeletedOccurrences', 'DeletedOccurrences', $f);
			}
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:Recurrence');
		}
        // execute command
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, null, $rm, $rd);
		// process response
        if ($rs->CalendarItem[0]) {
			$eo = clone $so;
			$eo->ID = $rs->CalendarItem[0]->ItemId->Id;
			$eo->CID = $cid;
			$eo->State = $rs->CalendarItem[0]->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($eo->Attachments) > 0) {
				// create attachments in remote data store
				$eo->Attachments = $this->createCollectionItemAttachment($eo->ID, $eo->Attachments);
				$eo->State = $eo->Attachments[0]->AffiliateState;
			}
            return $eo;
        } else {
            return null;
        }

    }

	/**
     * update collection item with uuid in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
	 * @param string $istate - Collection Item State
     * @param string $cid - Collection Item UUID
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItemUUID(string $cid, string $iid, string $istate, string $uuid): ?object {
		// request modifications array
        $rm = array();
        // construct update command object
        $rm[] = $this->updateFieldUnindexed('calendar:UID', 'UID', $uuid);
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, null, $rm, null);
        // return response
        if ($rs->CalendarItem[0]) {
            return (object) array('ID' => $rs->CalendarItem[0]->ItemId->Id, 'UID' => $uuid, 'State' => $rs->CalendarItem[0]->ItemId->ChangeKey);
        } else {
            return null;
        }
    }

    /**
     * delete collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $iid - Item ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollectionItem(string $iid): bool {
        // create object
        $o = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);

        $result = $this->RemoteCommonService->deleteItem($this->DataStore, array($o), 'HardDelete');

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
     * @param string $aid - Attachment ID
	 * 
	 * @return EventAttachmentObject
	 */
	public function fetchCollectionItemAttachment(array $batch): array {

		// check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
		// retrieve attachments
		$rs = $this->RemoteCommonService->fetchAttachment($this->DataStore, $batch);
		// construct response collection place holder
		$rc = array();
		// check for response
		if (isset($rs)) {
			// process collection of objects
			foreach($rs as $entry) {
				if (!isset($entry->ContentType) || $entry->ContentType == 'application/octet-stream') {
					$type = \OCA\EWS\Utile\MIME::fromFileName($entry->Name);
				} else {
					$type = $entry->ContentType;
				}
				// insert attachment object in response collection
				$rc[] = new EventAttachmentObject(
					'D',
					$entry->AttachmentId->Id, 
					$entry->Name,
					$type,
					'B',
					$entry->Size,
					$entry->Content
				);
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
	 * @param string $aid - Affiliation ID
     * @param array $sc - Collection of EventAttachmentObject(S)
	 * 
	 * @return string
	 */
	public function createCollectionItemAttachment(string $aid, array $batch): array {

		// check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
		// construct command collection place holder
		$cc = array();
		// process batch
		foreach ($batch as $key => $entry) {
			// construct command object
			$co = new \OCA\EWS\Components\EWS\Type\FileAttachmentType();
			$co->IsInline = false;
			$co->IsContactPhoto = false;
			$co->Name = $entry->Name;
			$co->ContentId = $entry->Name;
			$co->ContentType = $entry->Type;
			$co->Size = $entry->Size;
			
			switch ($entry->Encoding) {
				case 'B':
					$co->Content = $entry->Data;
					break;
				case 'B64':
					$co->Content = base64_decode($entry->Data);
					break;
			}
			// insert command object in to collection
			$cc[] = $co;
		}
		// execute command(s)
		$rs = $this->RemoteCommonService->createAttachment($this->DataStore, $aid, $cc);
		// construct results collection place holder
		$rc = array();
		// check for response
		if (isset($rs)) {
			// process collection of objects
			foreach($rs as $key => $entry) {
				$ro = clone $batch[$key];
				$ro->Id = $entry->AttachmentId->Id;
				$ro->Data = null;
				$ro->AffiliateId = $entry->AttachmentId->RootItemId;
				$ro->AffiliateState = $entry->AttachmentId->RootItemChangeKey;
				$rc[] = $ro;
			}

        }
		// return response collection
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
		// execute command
		$data = $this->RemoteCommonService->deleteAttachment($this->DataStore, $batch);

		return $data;

    }

	/**
     * construct collection of default remote collection properties 
     * 
     * @since Release 1.0.0
	 * 
	 * @return object
	 */
	public function constructDefaultCollectionProperties(): object {

		// evaluate if default collection properties collection exisits
		if (!isset($this->DefaultCollectionProperties)) {
			// unindexed property names collection
			$_properties = [
				'folder:FolderId',
				'folder:FolderClass',
				'folder:ParentFolderId',
				'folder:DisplayName',
				'folder:TotalCount',
			];
			// construct property collection
			$this->DefaultCollectionProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			foreach ($_properties as $entry) {
				$this->DefaultCollectionProperties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($entry);
			}
		}

		return $this->DefaultCollectionProperties;
		
	}

	/**
     * construct collection of default remote object properties 
     * 
     * @since Release 1.0.0
	 * 
	 * @return object
	 */
	public function constructDefaultItemProperties(): object {

		// evaluate if default item properties collection exisits
		if (!isset($this->DefaultItemProperties)) {
			// unindexed property names collection
			$_properties = [
				'item:ItemId',
				'calendar:UID',
				'item:ParentFolderId',
				'item:DateTimeCreated',
				'item:DateTimeSent',
				'item:LastModifiedTime',
				'calendar:TimeZone',
				'calendar:Start',
				'calendar:StartTimeZone',
				'calendar:End',
				'calendar:EndTimeZone',
				'item:Subject',
				'item:Body',
				'calendar:Location',
				'calendar:LegacyFreeBusyStatus',
				'item:Importance',
				'item:Sensitivity',
				'item:Categories',
				'calendar:Organizer',
				'calendar:RequiredAttendees',
				'calendar:OptionalAttendees',
				'item:ReminderIsSet',
				'item:ReminderMinutesBeforeStart',
				'calendar:Recurrence',
				'calendar:ModifiedOccurrences',
				'calendar:DeletedOccurrences',
				'item:Attachments',
				'calendar:IsAllDayEvent',

				'item:UniqueBody',
				'calendar:AppointmentState',
				'calendar:Resources',
			];
			// construct property collection
			$this->DefaultItemProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			foreach ($_properties as $entry) {
				$this->DefaultItemProperties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($entry);
			}

			// construct extended property collection
			$this->DefaultItemProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
				'PublicStrings',
				null,
				null,
				'DAV:id',
				null,
				'String'
			);
			$this->DefaultItemProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
				'PublicStrings',
				null,
				null,
				'DAV:uid',
				null,
				'String'
			);
		}

		return $this->DefaultItemProperties;

	}

	/**
     * construct collection item unindexed property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
     * @param string $name - property name
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldUnindexed(string $uri, string $name, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($uri);
        // create field contact object
        $o->CalendarItem = new \OCA\EWS\Components\EWS\Type\CalendarItemType();
        $o->CalendarItem->$name = $value;
        // return object
        return $o;
    }

    /**
     * construct collection item unindexed property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldUnindexed(string $uri): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($uri);
        // return object
        return $o;
    }

    /**
     * construct collection item indexed property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
     * @param string $index - property index
     * @param string $name - property name
     * @param string $dictionary - property dictionary object
     * @param string $entry - property entry object
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldIndexed(string $uri, string $index, string $name, mixed $dictionary, mixed $entry): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->IndexedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($uri, $index);
        // create field contact object
        $o->CalendarItem = new \OCA\EWS\Components\EWS\Type\CalendarItemType();
        $o->CalendarItem->$name = $dictionary;
        $o->CalendarItem->$name->Entry = $entry;
        // return object
        return $o;
    }

    /**
     * construct collection item indexed property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldIndexed(string $uri, string $index): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->IndexedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($uri, $index);
        // return object
        return $o;
    }

    /**
     * construct collection item extended property create command
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByName(string $collection, string $name, string $type, mixed $value): object {
        // create extended field object
        $o = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                null,
                $name,
                null,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByName(string $collection, string $name, string $type, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            null,
            $name,
            null,
            $type
        );
        // create field contact object
        $o->CalendarItem = new \OCA\EWS\Components\EWS\Type\CalendarItemType();
        $o->CalendarItem->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                null,
                $name,
                null,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByName(string $collection, string $name, string $type): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            null,
            $name,
            null,
            $type
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property create command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByTag(string $tag, string $type, mixed $value): object {
        // create extended field object
        $o = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                $tag,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByTag(string $tag, string $type, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            null,
            null,
            null,
            null,
            $tag,
            $type
        );
        // create field contact object
        $o->CalendarItem = new \OCA\EWS\Components\EWS\Type\CalendarItemType();
        $o->CalendarItem->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                $tag,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByTag(string $tag, string $type): object {
        // construct field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            null,
            null,
            null,
            null,
            $tag,
            $type
        );
        // return object
        return $o;
    }

	/**
     * construct collection item time zone property
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - time zone name
	 * 
	 * @return object collection item time zone property
	 */
    public function constructTimeZone(string $name): object {
		// retrive time zone properties
		$zone = \OCA\EWS\Utile\TimeZoneEWS::find($name);

        // construct time zone object
        $o = new \OCA\EWS\Components\EWS\Type\TimeZoneDefinitionType;
		$o->Id = $zone->Id;
		$o->Name = $zone->Name;

		// Periods
		$o->Periods = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPeriodsType();
		if (isset($zone->Periods->Period) && count($zone->Periods->Period) > 0) {
			foreach ($zone->Periods->Period as $entry) {
				$o->Periods->Period[] = new \OCA\EWS\Components\EWS\Type\PeriodType(
					$entry->Id,
					$entry->Name,
					$entry->Bias
				);
			}
		}

		// Transitions
		$o->Transitions = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsType();
		// Transition
		if (isset($zone->Transitions->Transition) && count($zone->Transitions->Transition) > 0) {
			foreach ($zone->Transitions->Transition as $entry) {
				$o->Transitions->Transition[] = new \OCA\EWS\Components\EWS\Type\TransitionType(
					new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
						$entry->To->Kind,
						$entry->To->_
					)
				);
			}
		}
		// Absolute Date Transition
		/*
		if (isset($zone->Transitions->AbsoluteDateTransition) && count($zone->Transitions->AbsoluteDateTransition) > 0) {
			foreach ($zone->Transitions->AbsoluteDateTransition as $entry) {
				$o->Transitions->AbsoluteDateTransition[] = new \OCA\EWS\Components\EWS\Type\AbsoluteDateTransitionType(
					new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
						$entry->To->Kind,
						$entry->To->_
					),
					$entry->DateTime
				);
			}
		}
		*/
		
		// Recurring Date Transition
		if (isset($zone->Transitions->RecurringDateTransition) && count($zone->Transitions->RecurringDateTransition) > 0) {
			foreach ($zone->Transitions->RecurringDateTransition as $entry) {
				$o->Transitions->RecurringDateTransition[] = new \OCA\EWS\Components\EWS\Type\RecurringDateTransitionType(
					new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
						$entry->To->Kind,
						$entry->To->_
					),
					$entry->TimeOffset,
					$entry->Month,
					$entry->Day
				);
			}
		}
		// Recurring Day Transition
		if (isset($zone->Transitions->RecurringDayTransition) && count($zone->Transitions->RecurringDayTransition) > 0) {
			foreach ($zone->Transitions->RecurringDayTransition as $entry) {
				$o->Transitions->RecurringDayTransition[] = new \OCA\EWS\Components\EWS\Type\RecurringDayTransitionType(
					new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
						$entry->To->Kind,
						$entry->To->_
					),
					$entry->TimeOffset,
					$entry->Month,
					$entry->DayOfWeek,
					$entry->Occurrence
				);
			}
		}

		// Transitions Groups
		if (isset($zone->TransitionsGroups->TransitionsGroup) && count($zone->TransitionsGroups->TransitionsGroup) > 0) {
			$o->TransitionsGroups = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsGroupsType();
			foreach ($zone->TransitionsGroups->TransitionsGroup as $key => $group) {
				$o->TransitionsGroups->TransitionsGroup[$key] = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsType();
				$o->TransitionsGroups->TransitionsGroup[$key]->Id = $group->Id;
				// Transition
				if (isset($group->Transition) && count($group->Transition) > 0) {
					foreach ($group->Transition as $entry) {
						$o->TransitionsGroups->TransitionsGroup[$key]->Transition[] = new \OCA\EWS\Components\EWS\Type\TransitionType(
							new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
								$entry->To->Kind,
								$entry->To->_
							)
						);
					}
				}
				// Absolute Date Transition
				if (isset($group->AbsoluteDateTransition) && count($group->AbsoluteDateTransition) > 0) {
					foreach ($group->AbsoluteDateTransition as $entry) {
						$o->TransitionsGroups->TransitionsGroup[$key]->AbsoluteDateTransition[] = new \OCA\EWS\Components\EWS\Type\AbsoluteDateTransitionType(
							new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
								$entry->To->Kind,
								$entry->To->_
							),
							$entry->DateTime
						);
					}
				}
				// Recurring Date Transition
				if (isset($group->RecurringDateTransition) && count($group->RecurringDateTransition) > 0) {
					foreach ($group->RecurringDateTransition as $entry) {
						$o->TransitionsGroups->TransitionsGroup[$key]->RecurringDateTransition[] = new \OCA\EWS\Components\EWS\Type\RecurringDateTransitionType(
							new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
								$entry->To->Kind,
								$entry->To->_
							),
							$entry->TimeOffset,
							$entry->Month,
							$entry->Day
						);
					}
				}
				// Recurring Day Transition
				if (isset($group->RecurringDayTransition) && count($group->RecurringDayTransition) > 0) {
					foreach ($group->RecurringDayTransition as $entry) {
						$o->TransitionsGroups->TransitionsGroup[$key]->RecurringDayTransition[] = new \OCA\EWS\Components\EWS\Type\RecurringDayTransitionType(
							new \OCA\EWS\Components\EWS\Type\TransitionTargetType(
								$entry->To->Kind,
								$entry->To->_
							),
							$entry->TimeOffset,
							$entry->Month,
							$entry->DayOfWeek,
							$entry->Occurrence
						);
					}
				}
			}
		}
		// return time zone definition object
        return $o;
    }

	/**
     * convert remote CalendarItemType object to EventObject
     * 
     * @since Release 1.0.0
     * 
	 * @param CalendarItemType $data - item as CalendarItemType object
	 * 
	 * @return EventObject item as EventObject
	 */
	public function toEventObject(CalendarItemType $data): EventObject {
		
		// create object
		$o = new EventObject();
		// Origin
		$o->Origin = 'R';
        // ID / State
        if (isset($data->ItemId)) {
            $o->ID = $data->ItemId->Id;
            $o->State = $data->ItemId->ChangeKey;
        }
		// UUID
		if (!empty($data->UID)) {
            $o->UUID = $this->fromUID($data->UID);
        }
        // Collection ID
        if (isset($data->ParentFolderId)) {
            $o->CID = $data->ParentFolderId->Id;
        }
        // Creation Date
        if (!empty($data->DateTimeCreated)) {
            $o->CreatedOn = new DateTime($data->DateTimeCreated);
        }
        // Modification Date
        if (!empty($data->DateTimeSent)) {
            $o->ModifiedOn = new DateTime($data->DateTimeSent);
        }
        if (!empty($data->LastModifiedTime)) {
            $o->ModifiedOn = new DateTime($data->LastModifiedTime);
        }
		// Start Time Zone
        if (!empty($data->StartTimeZone)) {
			$o->StartsTZ = $this->fromTimeZone($data->StartTimeZone->Id);
        }
		// End Time Zone
		if (!empty($data->EndTimeZone)) {
			$o->EndsTZ = $this->fromTimeZone($data->EndTimeZone->Id);
		}
		// Time Zone
        if (!empty($data->TimeZone)) {
        	$o->TimeZone = $this->fromTimeZone($data->TimeZone);
			if (isset($o->TimeZone)) {
				if (!isset($o->StartsTZ)) { $o->StartsTZ = clone $o->TimeZone; }
				if (!isset($o->EndsTZ)) { $o->EndsTZ = clone $o->TimeZone; }
			}
        }
		// Start Date/Time
		if (!empty($data->Start)) {
			$o->StartsOn = new DateTime($data->Start);
			//if (isset($o->StartsTZ)) { $o->StartsOn->setTimezone($o->StartsTZ); }
		}
		// End Date/Time
        if (!empty($data->End)) {
            $o->EndsOn = new DateTime($data->End);
			//if (isset($o->EndsTZ)) { $o->EndsOn->setTimezone($o->EndsTZ); }
        }
		// All Day Event
		if(isset($data->IsAllDayEvent) && $data->IsAllDayEvent == true) {
			$o->Span = 'F'; // Full
			//$o->StartsOn->setTime(0,0,0,0);
			//$o->EndsOn->setTime(0,0,0,0);
		}
		else {
			$o->Span = 'P'; // Partial
		}
		// Label
        if (!empty($data->Subject)) {
            $o->Label = $data->Subject;
        }
		// Notes
		if (!empty($data->Body)) {
			$o->Notes = $data->Body->_;
		}
		// Location
		if (!empty($data->Location)) {
			$o->Location = $data->Location;
		}
		// Availability
		if (!empty($data->LegacyFreeBusyStatus)) {
			$o->Availability = $data->LegacyFreeBusyStatus;
		}
		// Priority
		if (!empty($data->Importance)) {
			$o->Priority = $this->fromImportance($data->Importance);
		}
		// Sensitivity
		if (!empty($data->Sensitivity)) {
			$o->Sensitivity = $this->fromSensitivity($data->Sensitivity);
		}
		// Tag(s)
		if (isset($data->Categories)) {
			foreach($data->Categories->String as $entry) {
				$o->addTag($entry);
			}
		}
		// Organizer
		if (isset($data->Organizer)) {
			$o->Organizer->Address = $data->Organizer->Mailbox->EmailAddress;
			$o->Organizer->Name = $data->Organizer->Mailbox->Name;
		}
		// Attendee(s)
		if (isset($data->RequiredAttendees)) {
			foreach($data->RequiredAttendees->Attendee as $entry) {
				if ($entry->Mailbox->EmailAddress) {
					$o->addAttendee(
						$entry->Mailbox->EmailAddress, 
						$entry->Mailbox->Name, 
						'R', 
						$this->fromAttendeeResponse($entry->ResponseType));
				}
			}
		}
		if (isset($data->OptionalAttendees)) {
			foreach($data->OptionalAttendees->Attendee as $entry) {
				if ($entry->Mailbox->EmailAddress) {
					$o->addAttendee(
						$entry->Mailbox->EmailAddress, 
						$entry->Mailbox->Name, 
						'O', 
						$this->fromAttendeeResponse($entry->ResponseType));
				}
			}
		}
		// Notification(s)
		if (isset($data->ReminderIsSet) && isset($data->ReminderMinutesBeforeStart)) { 
			$w = new DateInterval('PT' . $data->ReminderMinutesBeforeStart . 'M');
			$w->invert = 1;
			$o->addNotification(
				'D',
				'R',
				$w
			);
		}
		// Occurrence
        if (isset($data->Recurrence)) {
			// Iterations
			if (isset($data->Recurrence->NumberedRecurrence->NumberOfOccurrences)) {
				$o->Occurrence->Iterations = $data->Recurrence->NumberedRecurrence->NumberOfOccurrences;
			}
			// Conclusion
			if (isset($data->Recurrence->EndDateRecurrence->EndDate)) {
				$o->Occurrence->Concludes = new DateTime($data->Recurrence->EndDateRecurrence->EndDate);
			}
			// Daily
			if (isset($data->Recurrence->DailyRecurrence)) {
				
				$o->Occurrence->Pattern = 'A';
				$o->Occurrence->Precision = 'D';

				if (isset($data->Recurrence->DailyRecurrence->Interval)) {
					$o->Occurrence->Interval = $data->Recurrence->DailyRecurrence->Interval;
				}
				if (isset($data->Recurrence->DailyRecurrence->NumberOfOccurrences)) {
					$o->Occurrence->Iterations = $data->Recurrence->DailyRecurrence->NumberOfOccurrences;
				}
            }
			// Weekly
			if (isset($data->Recurrence->WeeklyRecurrence)) {
				
				$o->Occurrence->Pattern = 'A';
				$o->Occurrence->Precision = 'W';
				
				if (isset($data->Recurrence->WeeklyRecurrence->Interval)) {
					$o->Occurrence->Interval = $data->Recurrence->WeeklyRecurrence->Interval;	
				}
				if (isset($data->Recurrence->WeeklyRecurrence->NumberOfOccurrences)) {
					$o->Occurrence->Iterations = $data->Recurrence->WeeklyRecurrence->NumberOfOccurrences;
				}
				if (isset($data->Recurrence->WeeklyRecurrence->DaysOfWeek)) {
					$o->Occurrence->OnDayOfWeek = $this->fromDaysOfWeek($data->Recurrence->WeeklyRecurrence->DaysOfWeek);
				}
            }
			// Monthly Absolute
			if (isset($data->Recurrence->AbsoluteMonthlyRecurrence)) {
				
				$o->Occurrence->Pattern = 'A';
				$o->Occurrence->Precision = 'M';
				
				if (isset($data->Recurrence->AbsoluteMonthlyRecurrence->Interval)) {
					$o->Occurrence->Interval = $data->Recurrence->AbsoluteMonthlyRecurrence->Interval;	
				}
				if (isset($data->Recurrence->AbsoluteMonthlyRecurrence->NumberOfOccurrences)) {
					$o->Occurrence->Iterations = $data->Recurrence->AbsoluteMonthlyRecurrence->NumberOfOccurrences;
				}
				if (isset($data->Recurrence->AbsoluteMonthlyRecurrence->DayOfMonth)) {
					$o->Occurrence->OnDayOfMonth = $this->fromDaysOfMonth($data->Recurrence->AbsoluteMonthlyRecurrence->DayOfMonth);
				}
            }
			// Monthly Relative
			if (isset($data->Recurrence->RelativeMonthlyRecurrence)) {
				
				$o->Occurrence->Pattern = 'R';
				$o->Occurrence->Precision = 'M';
				
				if (isset($data->Recurrence->RelativeMonthlyRecurrence->Interval)) {
					$o->Occurrence->Interval = $data->Recurrence->RelativeMonthlyRecurrence->Interval;	
				}
				if (isset($data->Recurrence->RelativeMonthlyRecurrence->DaysOfWeek)) {
					$o->Occurrence->OnDayOfWeek = $this->fromDaysOfWeek($data->Recurrence->RelativeMonthlyRecurrence->DaysOfWeek, true);
				}
				if (isset($data->Recurrence->RelativeMonthlyRecurrence->DayOfWeekIndex)) {
					$o->Occurrence->OnWeekOfMonth = $this->fromWeekOfMonth($data->Recurrence->RelativeMonthlyRecurrence->DayOfWeekIndex);
				}
            }
			// Yearly Absolute
			if (isset($data->Recurrence->AbsoluteYearlyRecurrence)) {
				
				$o->Occurrence->Pattern = 'A';
				$o->Occurrence->Precision = 'Y';
				
				if (isset($data->Recurrence->AbsoluteYearlyRecurrence->Interval)) {
					$o->Occurrence->Interval = $data->Recurrence->AbsoluteYearlyRecurrence->Interval;	
				}
				if (isset($data->Recurrence->AbsoluteYearlyRecurrence->NumberOfOccurrences)) {
					$o->Occurrence->ExpiresCount = $data->Recurrence->AbsoluteYearlyRecurrence->NumberOfOccurrences;
				}
				if (isset($data->Recurrence->AbsoluteYearlyRecurrence->Month)) {
					$o->Occurrence->OnMonthOfYear = $this->fromMonthOfYear($data->Recurrence->AbsoluteYearlyRecurrence->Month);
				}
				if (isset($data->Recurrence->AbsoluteYearlyRecurrence->DayOfMonth)) {
					$o->Occurrence->OnDayOfMonth = $this->fromDaysOfMonth($data->Recurrence->AbsoluteYearlyRecurrence->DayOfMonth);
				}
            }
			// Yearly Relative
			if (isset($data->Recurrence->RelativeYearlyRecurrence)) {
				
				$o->Occurrence->Pattern = 'R';
				$o->Occurrence->Precision = 'Y';
				
				if (isset($data->Recurrence->RelativeYearlyRecurrence->DaysOfWeek)) {
					$o->Occurrence->OnDayOfWeek = $this->fromDaysOfWeek($data->Recurrence->RelativeYearlyRecurrence->DaysOfWeek, true);
				}
				if (isset($data->Recurrence->RelativeYearlyRecurrence->DayOfWeekIndex)) {
					$o->Occurrence->OnWeekOfMonth = $this->fromWeekOfMonth($data->Recurrence->RelativeYearlyRecurrence->DayOfWeekIndex);
				}
				if (isset($data->Recurrence->RelativeYearlyRecurrence->Month)) {
					$o->Occurrence->OnMonthOfYear = $this->fromMonthOfYear($data->Recurrence->RelativeYearlyRecurrence->Month);
				}
            }
			// Excludes
			if (isset($data->DeletedOccurrences)) {
				foreach($data->DeletedOccurrences->DeletedOccurrence as $entry) {
					if (isset($entry->Start)) {
						$o->Occurrence->Excludes[] = new DateTime($entry->Start);
					}
				}
			}
        }
        // Attachment(s)
		if (isset($data->Attachments) && is_array($data->Attachments)) {
			foreach($data->Attachments->FileAttachment as $entry) {
				if ($entry->ContentType == 'application/octet-stream') {
					$type = \OCA\EWS\Utile\MIME::fromFileName($entry->Name);
				} else {
					$type = $entry->ContentType;
				}
				$o->addAttachment(
					'D',
					$entry->AttachmentId->Id, 
					$entry->Name,
					$type,
					'B',
					$entry->Size
				);
			}
		}
        // Extended Properties
		if (isset($data->ExtendedProperty)) {
			foreach ($data->ExtendedProperty as $entry) {
				switch ($entry->ExtendedFieldURI->PropertyName) {
					case 'DAV:uid':
						//$o->UUID = $entry->Value;
						break;
				}
				switch ($entry->ExtendedFieldURI->PropertyTag) {
					case '0x3007':
						$o->CreatedOn = new DateTime($entry->Value);
						break;
					case '0x3008':
						$o->ModifiedOn = new DateTime($entry->Value);
						break;
				}
			}
		}

		return $o;

    }

	/**
     * Converts EWS (Microsoft/Windows) time zone name to DateTimeZone object
     * 
     * @since Release 1.0.0
     * 
     * @param string $zone  ews time zone name
     * 
     * @return DateTimeZone valid DateTimeZone object on success, or null on failure
     */
	public function fromTimeZone(string $name): ?DateTimeZone {
		
		// convert EWS time zone name to DateTimeZone object	
		return \OCA\EWS\Utile\TimeZoneEWS::toDateTimeZone($name);

	}

	/**
     * Converts DateTimeZone object to EWS (Microsoft/Windows) time zone name
     * 
     * @since Release 1.0.0
     * 
     * @param DateTimeZone $zone
     * 
     * @return string valid EWS time zone name on success, or null on failure
     */ 
	public function toTimeZone(DateTimeZone $zone): ?string {

		// convert DateTimeZone object to EWS time zone name
		return \OCA\EWS\Utile\TimeZoneEWS::fromDateTimeZone($zone);

	}

	/**
     * Converts remote guid format to local uuid format
     * 
     * @since Release 1.0.15
     * 
     * @param string $value
     * 
     * @return string
     */
	public function fromUID(string $value): ?string {
		
		//https://learn.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-asemail/e7424ddc-dd10-431e-a0b7-5c794863370e
		//https://docs.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-oxocal/1d3aac05-a7b9-45cc-a213-47f0a0a2c5c1

		if (strlen($value) == 112 || (strlen($value) == 56 && mb_detect_encoding((string)$value, null, true) === false)) {
			$value = (strlen($value) == 112) ? substr($value, 80, 32) : substr(bin2hex($value), 80, 32);
			return (substr($value, 0, 8) . '-' . 
					substr($value, 8, 4) . '-' .
					substr($value, 12, 4) . '-' .
					substr($value, 16, 4) . '-' .
					substr($value, 20, 12));
		}
		elseif (strlen($value) == 91 && mb_detect_encoding((string)$value, null, true) === false) {
			$value = substr($value, 53, 36);
			return $value;
		}
		elseif (\OCA\EWS\Utile\Validator::uuid_long($value)) {
			return $value;
		}
		elseif (\OCA\EWS\Utile\Validator::uuid_short($value)) {
			return $value;
		}
		return null;

	}

	/**
     * Converts local uuid format to remote guid format
     * 
     * @since Release 1.0.15
     * 
     * @param string $value
     * 
     * @return string 
     */ 
	public function toUID(string $value, string $type = 'SB'): ?string {

		// https://learn.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-asemail/e7424ddc-dd10-431e-a0b7-5c794863370e
		// https://docs.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-oxocal/1d3aac05-a7b9-45cc-a213-47f0a0a2c5c1

		// Blob Id + Instance Date (YYYY-MM-DD) + Creation Stamp (YYYY-MM-DD-HH-MM-SS) + Padding
		$prefix = '040000008200E00074C5B7101A82E008' . '00000000' . '0000000000000000' . '0000000000000000';

		if ($type == 'SH') {
			// Prefix + Size + Data
			return $prefix . '10000000' . strtoupper(str_replace('-', '', $value));
		}
		elseif ($type == 'SB') {
			// Prefix + Size + Data
			return hex2bin($prefix . '10000000' . strtoupper(str_replace('-', '', $value)));
		}
		elseif ($type == 'LH') {
			// Prefix + Size + Data
			return $prefix . '33000000' . bin2hex('vCal-Uid') . '01000000' . bin2hex('{' . strtoupper($value) . '}') . '00';
		}
		elseif ($type == 'LB') {
			// Prefix + Size + Data
			return hex2bin($prefix . '33000000') . 'vCal-Uid' . hex2bin('01000000') . '{' . strtoupper($value) . '}' . hex2bin('00');
		}

		return null;

	}

	/**
     * convert remote days of the week to event object days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $days - remote days of the week values(s)
	 * @param bool $group - flag to check if days are grouped
	 * 
	 * @return array event object days of the week values(s)
	 */
	public function fromDaysOfWeek(string $days, bool $group = false ): array {

		// days conversion reference
		$_tm = array(
			'Monday' => 1,
			'Tuesday' => 2,
			'Wednesday' => 3,
			'Thursday' => 4,
			'Friday' => 5,
			'Saturday' => 6,
			'Sunday' => 7,
			'Day' => 0,
			'Weekday' => 8,
			'WeekendDay' => 9
		);
		// convert days to array
		$days = explode(' ', $days);
		// evaluate if days match any group patterns
		if ($group && count($days) == 1) {
			$groups = array(
				'Day' => array(1,2,3,4,5,6,7),
				'Weekday' => array(1,2,3,4,5),
				'WeekendDay' => array(6,7)
			);
			if (isset($groups[$days[0]])) {
				return $groups[$days[0]];
			}
		}
		// convert day values
		foreach ($days as $key => $entry) {
			if (isset($_tm[$entry])) {
				$days[$key] = $_tm[$entry];
			}
		}
		// return converted days
		return $days;

	}

	/**
     * convert event object days of the week to remote days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - event object days of the week values(s)
	 * @param bool $group - flag to check if days can be grouped 
	 * 
	 * @return string remote days of the week values(s)
	 */
	public function toDaysOfWeek(array $days, bool $group = false): string {
		
		// days conversion reference
		$_tm = array(
			1 => 'Monday',
			2 => 'Tuesday',
			3 => 'Wednesday',
			4 => 'Thursday',
			5 => 'Friday',
			6 => 'Saturday',
			7 => 'Sunday',
			0 => 'Day',
			8 => 'Weekday',
			9 => 'WeekendDay'
		);
		// evaluate if days match any group patterns
		if ($group) {
			$groups = array(
				'Day' => array(1,2,3,4,5,6,7),
				'Weekday' => array(1,2,3,4,5),
				'WeekendDay' => array(6,7)
			);
			sort($days);
			foreach ($groups as $key => $entry) {
				if ($days == $entry) {
					return $key;
				}
			}
		}
        // convert day values
        foreach ($days as $key => $entry) {
            if (isset($_tm[$entry])) {
                $days[$key] = $_tm[$entry];
            }
        }
        // convert days to string
        $days = implode(' ', $days);
        // return converted days
        return $days;

	}

	/**
     * convert remote days of the month to event object days of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $days - remote days of the month values(s)
	 * 
	 * @return array event object days of the month values(s)
	 */
	public function fromDaysOfMonth(string $days): array {

		// convert days to array
		$days = explode(' ', $days);
		// return converted days
		return $days;

	}

	/**
     * convert event object days of the month to remote days of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - event object days of the month values(s)
	 * 
	 * @return string remote days of the month values(s)
	 */
	public function toDaysOfMonth(array $days): string {

        // convert days to string
        $days = implode(' ', $days);
        // return converted days
        return $days;

	}

	/**
     * convert remote week of the month to event object week of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $weeks - remote week of the month values(s)
	 * 
	 * @return array event object week of the month values(s)
	 */
	public function fromWeekOfMonth(string $weeks): array {

		// weeks conversion reference
		$_tm = array(
			'First' => 1,
			'Second' => 2,
			'Third' => 3,
			'Fourth' => 4,
			'Last' => -1
		);
		// convert weeks to array
		$weeks = explode(' ', $weeks);
		// convert week values
		foreach ($weeks as $key => $entry) {
			if (isset($_tm[$entry])) {
				$weeks[$key] = $_tm[$entry];
			}
		}
		// return converted weeks
		return $weeks;

	}

	/**
     * convert event object week of the month to remote week of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $weeks - event object week of the month values(s)
	 * 
	 * @return string remote week of the month values(s)
	 */
	public function toWeekOfMonth(array $weeks): string {

		// weeks conversion reference
		$_tm = array(
			1 => 'First',
			2 => 'Second',
			3 => 'Third',
			4 => 'Fourth',
			-1 => 'Last',
			-2 => 'Fourth'
		);
		// convert week values
        foreach ($weeks as $key => $entry) {
            if (isset($_tm[$entry])) {
                $weeks[$key] = $_tm[$entry];
            }
        }
        // convert weeks to string
        $weeks = implode(',', $weeks);
        // return converted weeks
        return $weeks;

	}

	/**
     * convert remote month of the year to event object month of the year
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $months - remote month of the year values(s)
	 * 
	 * @return array event object month of the year values(s)
	 */
	public function fromMonthOfYear(string $months): array {

		// months conversion reference
		$_tm = array(
			'January' => 1,
			'February' => 2,
			'March' => 3,
			'April' => 4,
			'May' => 5,
			'June' => 6,
			'July' => 7,
			'August' => 8,
			'September' => 9,
			'October' => 10,
			'November' => 11,
			'December' => 12
		);
		// convert months to array
		$months = explode(' ', $months);
		// convert month values
		foreach ($months as $key => $entry) {
			if (isset($_tm[$entry])) {
				$months[$key] = $_tm[$entry];
			}
		}
		// return converted months
		return $months;

	}

	/**
     * convert event object month of the year to remote month of the year
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $weeks - event object month of the year values(s)
	 * 
	 * @return string remote month of the year values(s)
	 */
	public function toMonthOfYear(array $months): string {

		// months conversion reference
		$_tm = array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		);
		// convert month values
        foreach ($months as $key => $entry) {
            if (isset($_tm[$entry])) {
                $months[$key] = $_tm[$entry];
            }
        }
        // convert months to string
        $months = implode(',', $months);
        // return converted months
        return $months;

	}

	/**
     * convert remote sensitivity to event object sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - remote sensitivity value
	 * 
	 * @return int event object sensitivity value
	 */
	public function fromSensitivity(?string $level): int {
		
		// sensitivity conversion reference
		$levels = array(
			'Normal' => 0,
			'Personal' => 1,
			'Private' => 2,
			'Confidential' => 3
		);
		// evaluate if sensitivity value exists
		if (isset($levels[$level])) {
			// return converted sensitivity value
			return $levels[$level];
		} else {
			// return default sensitivity value
			return 0;
		}
		
	}

	/**
     * convert event object sensitivity to remote sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - event object sensitivity value
	 * 
	 * @return string remote sensitivity value
	 */
	public function toSensitivity(?int $level): string {
		
		// sensitivity conversion reference
		$levels = array(
			0 => 'Normal',
			1 => 'Personal',
			2 => 'Private',
			3 => 'Confidential'
		);
		// evaluate if sensitivity value exists
		if (isset($levels[$level])) {
			// return converted sensitivity value
			return $levels[$level];
		} else {
			// return default sensitivity value
			return 'Normal';
		}

	}

	/**
     * convert remote importance to event object priority
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - remote importance value
	 * 
	 * @return int event object priority value
	 */
	public function fromImportance(?string $level): int {
		
		// EWS: 0 = low, 1 = normal (default), 2 = high
		// VEVENT: 0 = undefined, 1-3 = high, 4-6 = normal, 7-9 = low

		// evaluate remote level and return local equvialent
		if ($level == 'High') {
			return 2;		// high priority
		}
		elseif ($level == 'Low') {
			return 8;		// low priority
		}
		else {
			return 5;		// normal priority
		}
		
	}

	/**
     * convert event object priority to remote importance
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - event object priority value
	 * 
	 * @return string remote importance value
	 */
	public function toImportance(?int $level): string {

		// EWS: 0 = low, 1 = normal (default), 2 = high
		// VEVENT: 0 = undefined, 1-3 = high, 4-6 = normal, 7-9 = low

		// evaluate local level and return remote equvialent
		if ($level > 0 && $level < 4) {
			return 'High';		// high priority
		}
		elseif ($level > 6 && $level < 10) {
			return 'Low';		// low priority
		}
		else {
			return 'Normal';	// normal priority
		}

	}

	/**
     * convert remote attendee response to event object response
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $response - remote attendee response value
	 * 
	 * @return string event object attendee response value
	 */
	public function fromAttendeeResponse(?string $response): string {
		
		// response conversion reference
		$responses = array(
			'Accept' => 'A',
			'Decline' => 'D',
			'Tentative' => 'T',
			'Organizer' => 'O',
			'Unknown' => 'U',
			'NoResponseReceived' => 'N'
		);
		// evaluate if response value exists
		if (isset($responses[$response])) {
			// return converted response value
			return $responses[$response];
		} else {
			// return default response value
			return 'N';
		}
		
	}

	/**
     * convert event object attendee response to remote attendee response
	 * 
     * @since Release 1.0.0
     * 
	 * @param string $response - event object attendee response value
	 * 
	 * @return string remote attendee response value
	 */
	public function toAttendeeResponse(?string $response): string {
		
		// response conversion reference
		$responses = array(
			'A' => 'Accept',
			'D' => 'Decline',
			'T' => 'Tentative',
			'O' => 'Organizer',
			'U' => 'Unknown',
			'N' => 'NoResponseReceived'
		);
		// evaluate if response value exists
		if (isset($responses[$response])) {
			// return converted response value
			return $responses[$response];
		} else {
			// return default response value
			return 'NoResponseReceived';
		}

	}

}
