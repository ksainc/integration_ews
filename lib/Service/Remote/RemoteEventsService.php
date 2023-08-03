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
	private $logger;
	/**
	 * @var RemoteCommonService
	 */
	private $RemoteCommonService;
	/**
	 * @var DateTimeZone
	 */
	private ?DateTimeZone $DefaultTimeZone = null;
    /**
	 * @var DateTimeZone
	 */
	public ?DateTimeZone $UserTimeZone = null;
	/**
	 * @var EWSClient
	 */
	public ?EWSClient $DataStore = null;
	/**
	 * @var Object
	 */
	private ?object $DefaultCollectionProperties = null;
	/**
	 * @var Object
	 */
	private ?object $DefaultItemProperties = null;
	


	public function __construct (string $appName,
								LoggerInterface $logger,
								RemoteCommonService $RemoteCommonService) {
		$this->logger = $logger;
		$this->RemoteCommonService = $RemoteCommonService;
		$this->DefaultTimeZone = new DateTimeZone(date_default_timezone_get());
	}

	/**
	 * retrieve list of collections in remote storage
	 *
	 * @return array of collections and properties
	 */
	public function listCollections(): array {

		// execute command
		$cr = $this->RemoteCommonService->fetchFoldersByType($this->DataStore, 'IPF.Appointment', 'I', $this->constructDefaultCollectionProperties());
		// process response
		$cl = array();
		if (isset($cr)) {
			foreach ($cr->CalendarFolder as $folder) {
				$cl[] = array('id'=>$folder->FolderId->Id, 'name'=>$folder->DisplayName,'count'=>$folder->TotalCount);
			}
		}
		// return collections
		return $cl;

	}

	/**
	 * retrieve properties for specific collection
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
	 * @param string $cid - Collection Item ID
	 * 
	 * @return EventCollectionObject
	 */
	public function createCollection(string $cid, string $name, bool $ctype = false): ?EventCollectionObject {
        
		// construct command object
		$ec = new \OCA\EWS\Components\EWS\Type\CalendarFolderType();
		$ec->DisplayName = $name;
		$ec->FolderClass = 'IPF.Appointment';
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
     * @param string $cid - Collection ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollection(string $cid) : bool {
        
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
	 * @param string $cid - Collection ID
	 * 
	 * @return array
	 */
	public function fetchCollectionItemsUUID(string $cid, bool $ctype = false): array {

        // define place holders
        $data = array();
        $offset = 0;

        do {
            // execute command
            $ro = $this->RemoteCommonService->fetchItemsIds($this->DataStore, $cid, $ctype, $offset);
            // validate response object
            if (isset($ro) && count($ro->CalendarItem) > 0) {
                foreach ($ro->CalendarItem as $entry) {
                    if ($entry->ExtendedProperty) {
                        $data[] = array('ID'=>$entry->ItemId->Id, 'UUID'=>$entry->ExtendedProperty[0]->Value);
                    }
                }
                $offset += count($ro->CalendarItem);
            }
        }
        while (isset($ro) && count($ro->CalendarItem) > 0);
        // return
		return $data;
    }

	/**
     * retrieve collection item in remote storage
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
	 * @param string $cid - Collection ID
     * @param string $uuid -Collection Item UUID
	 * 
	 * @return EventObject
	 */
	public function fetchCollectionItemByUUID(string $cid, string $uuid): ?EventObject {

        // retrieve properties for a specific collection item
		$data = $this->RemoteCommonService->findItemByUUID($this->DataStore, $cid, $uuid, false, 'D', $this->constructDefaultItemProperties());
		// process response
		if (isset($data) && (count($data) > 0)) {
			// convert to event object
            $eo = $this->toEventObject($data[0]);
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
				$tz = $this->DefaultTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if ($tz instanceof \DateTimeZone) {
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
				$tz = $this->DefaultTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if ($tz instanceof \DateTimeZone) {
				$ro->EndTimeZone = $this->constructTimeZone($tz);
			} else {
				$ro->EndTimeZone = $this->constructTimeZone('UTC');
			}
			unset($tz);
			unset($dt);
		}
		// All Day Event
		if(($so->EndsTZ == $so->StartsTZ) &&
		   (fmod(($so->EndsOn->getTimestamp() - $so->StartsOn->getTimestamp()), 86400) == 0)) {
			$ro->IsAllDayEvent = 'true';
		}
		else {
			$ro->IsAllDayEvent = 'false';
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
			$ro->ReminderIsSet  = 'true';
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil(($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp() / 60));
				$ro->ReminderMinutesBeforeStart = $t;
				unset($t);
			}
			elseif ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'R') {
				if ($so->Notifications[0]->When->invert == 0) {
					$t = ($so->Notifications[0]->When->y * -525600) +
						($so->Notifications[0]->When->m * -43800) +
						($so->Notifications[0]->When->d * -1440) +
						($so->Notifications[0]->When->h * -60) +
						($so->Notifications[0]->When->i * -1);
				} else {
					$t = ($so->Notifications[0]->When->y * 525600) +
						($so->Notifications[0]->When->m * 43800) +
						($so->Notifications[0]->When->d * 1440) +
						($so->Notifications[0]->When->h * 60) +
						($so->Notifications[0]->When->i);
				}
				$ro->ReminderMinutesBeforeStart = $t;
				unset($t);
			}
		}
		// Occurrence
		if (isset($so->Occurrence) && !empty($so->Occurrence->Precision)) {

			$ro->Recurrence = new \OCA\EWS\Components\EWS\Type\RecurrenceType();

			// Occurrence Iterations
			if (!empty($so->Occurrence->Iterations)) {
				$ro->Recurrence->NumberedRecurrence = new \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType();
				$ro->Recurrence->NumberedRecurrence->NumberOfOccurrences = $so->Occurrence->Iterations;
			}
			// Occurrence Conclusion
			if (!empty($so->Occurrence->Concludes)) {
				$ro->Recurrence->EndDateRecurrence = new \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType();
				if ($so->Origin == 'L') {
					// subtract 1 day to adjust in how the end date is calculated in NC and EWS
					$ro->Recurrence->EndDateRecurrence->EndDate = date_modify(clone $so->Occurrence->Concludes, '-1 day')->format('Y-m-d\TH:i:s');
				}
				else {
					$ro->Recurrence->EndDateRecurrence->EndDate = $so->Occurrence->Concludes->format('Y-m-d\TH:i:s');
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
        if ($rs->ItemId) {
			$eo = clone $so;
			$eo->ID = $rs->ItemId->Id;
			$eo->CID = $cid;
			$eo->State = $rs->ItemId->ChangeKey;
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
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
     * @param EventObject $so - Source Data
	 * 
	 * @return EventObject
	 */
	public function updateCollectionItem(string $cid, string $iid, EventObject $so): ?EventObject {

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
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:TimeZone');
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
				$tz = $this->DefaultTimeZone;
			}
			// convert time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if ($tz instanceof \DateTimeZone) {
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
        else {
            $rd[] = $this->deleteFieldUnindexed('calendar:Start');
			$rd[] = $this->deleteFieldUnindexed('calendar:StartTimeZone');
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
				$tz = $this->DefaultTimeZone;
			}
			// construct start time zone
			$tz = $this->toTimeZone($tz);
			// construct time zone attribute
			if ($tz instanceof \DateTimeZone) {
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
        else {
            $rd[] = $this->deleteFieldUnindexed('calendar:End');
			$rd[] = $this->deleteFieldUnindexed('calendar:EndTimeZone');
        }
		// All Day Event
		if(($so->EndsTZ == $so->StartsTZ) &&
		   (fmod(($so->EndsOn->getTimestamp() - $so->StartsOn->getTimestamp()), 86400) == 0) ) {
			$rm[] = $this->updateFieldUnindexed('calendar:IsAllDayEvent', 'IsAllDayEvent', 'true');
		}
		else {
			$rm[] = $this->updateFieldUnindexed('calendar:IsAllDayEvent', 'IsAllDayEvent', 'false');
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
                    'Text',
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
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:LegacyFreeBusyStatus');
		}
		// Priority
		if (!empty($so->Priority)) {
			$rm[] = $this->updateFieldUnindexed('item:Importance', 'Importance', $this->toImportance($so->Priority));
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('item:Importance');
		}
		// Sensitivity
		if (!empty($so->Sensitivity)) {
			$rm[] = $this->updateFieldUnindexed('item:Sensitivity', 'Sensitivity', $this->toSensitivity($so->Sensitivity));
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('item:Sensitivity');
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
			$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', 'true');
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil(($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp() / 60));
				$rm[] = $this->updateFieldUnindexed(
					'item:ReminderMinutesBeforeStart',
					'ReminderMinutesBeforeStart',
					$t
				);
				unset($t);
			}
			elseif ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'R') {
				if ($so->Notifications[0]->When->invert == 0) {
					$t = ($so->Notifications[0]->When->y * -525600) +
						($so->Notifications[0]->When->m * -43800) +
						($so->Notifications[0]->When->d * -1440) +
						($so->Notifications[0]->When->h * -60) +
						($so->Notifications[0]->When->i * -1);
				} else {
					$t = ($so->Notifications[0]->When->y * 525600) +
						($so->Notifications[0]->When->m * 43800) +
						($so->Notifications[0]->When->d * 1440) +
						($so->Notifications[0]->When->h * 60) +
						($so->Notifications[0]->When->i);
				}
				$rm[] = $this->updateFieldUnindexed(
					'item:ReminderMinutesBeforeStart',
					'ReminderMinutesBeforeStart',
					(string) $t
				);
				unset($t);
			}
		}
		else {
			$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', 'false');
			$rd[] = $this->deleteFieldUnindexed('item:ReminderMinutesBeforeStart');
		}
		// Occurrence
		if (isset($so->Occurrence) && !empty($so->Occurrence->Precision)) {
			// construct recurrence object
			$f = new \OCA\EWS\Components\EWS\Type\RecurrenceType();
			// Iterations
			if (!empty($so->Occurrence->Iterations)) {
				$f->NumberedRecurrence = new \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType();
				$f->NumberedRecurrence->NumberOfOccurrences = $so->Occurrence->Iterations;
			}
			// Conclusion
			if (!empty($so->Occurrence->Concludes)) {
				$f->EndDateRecurrence = new \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType();
				if ($so->Origin == 'L') {
					// subtract 1 day to adjust in how the end date is calculated in NC and EWS
					$f->EndDateRecurrence->EndDate = date_modify(clone $so->Occurrence->Concludes, '-1 day')->format('Y-m-d\TH:i:s');
				}
				else {
					$f->EndDateRecurrence->EndDate = $so->Occurrence->Concludes->format('Y-m-d\TH:i:s');
				}
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
			} else {
				$rd[] = $this->deleteFieldUnindexed('calendar:DeletedOccurrences');	
			}
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('calendar:Recurrence');
			$rd[] = $this->deleteFieldUnindexed('calendar:DeletedOccurrences');
		}
        // execute command
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, $rd);
		// process response
        if ($rs->ItemId) {
			$eo = clone $so;
			$eo->ID = $rs->ItemId->Id;
			$eo->CID = $cid;
			$eo->State = $rs->ItemId->ChangeKey;
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
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
     * @param string $cid - Collection Item UUID
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItemUUID(string $cid, string $iid, string $uuid) : ?object {
		// request modifications array
        $rm = array();
        // construct update command object
        $rm[] = $this->updateFieldUnindexed('calendar:UID', 'UID', $uuid);
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, null);
        // return response
        if ($rs->ItemId) {
            return (object) array('ID' => $rs->ItemId->Id, 'UID' => $uuid, 'State' => $rs->ItemId->ChangeKey);
        } else {
            return null;
        }
    }

    /**
     * delete collection item in remote storage
     * 
     * @param string $iid - Item ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollectionItem(string $iid) : bool {
        // create object
        $o = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);

        $result = $this->RemoteCommonService->deleteItem($this->DataStore, array($o));

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

	/**
     * retrieve collection item attachment from local storage
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
     * construct collection item unindexed property update command
     * 
     * @param string $uri - property uri
     * @param string $name - property name
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldUnindexed(string $uri, string $name, mixed $value) : object {
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
     * @param string $uri - property uri
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldUnindexed(string $uri) : object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($uri);
        // return object
        return $o;
    }

    /**
     * construct collection item indexed property update command
     * 
     * @param string $uri - property uri
     * @param string $index - property index
     * @param string $name - property name
     * @param string $dictionary - property dictionary object
     * @param string $entry - property entry object
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldIndexed(string $uri, string $index, string $name, mixed $dictionary, mixed $entry) : object {
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
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldIndexed(string $uri, string $index) : object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->IndexedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($uri, $index);
        // return object
        return $o;
    }

    /**
     * construct collection item extended property create command
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByName(string $collection, string $name, string $type, mixed $value) : object {
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
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByName(string $collection, string $name, string $type, mixed $value) : object {
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
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByName(string $collection, string $name, string $type) : object {
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
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByTag(string $tag, string $type, mixed $value) : object {
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
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByTag(string $tag, string $type, mixed $value) : object {
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
     * @param string $tag - property tag
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByTag(string $tag, string $type) : object {
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
     * @param string $tag - time zone name
	 * 
	 * @return object collection item time zone property
	 */
    public function constructTimeZone(string $name) : object {
		// retrive time zone properties
		$zone = \OCA\EWS\Utile\TimeZoneEWS::find($name);
        // construct time zone object
        $o = new \OCA\EWS\Components\EWS\Type\TimeZoneDefinitionType;
		$o->Id = $zone->id;

		if (!empty($zone->StandardBias) && !empty($zone->DaylightBias)) {
			$o->Periods = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPeriodsType();
			$o->Periods->Period[] = new \OCA\EWS\Components\EWS\Type\PeriodType(
				$zone->StandardName,
				$zone->StandardBias,
				'ST'
			);
			$o->Periods->Period[] = new \OCA\EWS\Components\EWS\Type\PeriodType(
				$zone->DaylightName,
				$zone->DaylightBias,
				'DL'
			);
			$o->TransitionsGroups = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsGroupsType();
			$group = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsType();
			$group->Id = 0;

			$transition = new \OCA\EWS\Components\EWS\Type\RecurringDayTransitionType();
			$transition->To = new \OCA\EWS\Components\EWS\Type\TransitionTargetType();
			$transition->To->_ = 'ST';
			$transition->To->Kind = 'Period';
			$transition->Month = $zone->DaylightEndMonth;
			$transition->Occurrence = $zone->DaylightEndWeek;
			$transition->DayOfWeek = $zone->DaylightEndDay;
			$transition->TimeOffset = $zone->DaylightEndTime;
			$group->RecurringDayTransition[] = $transition;

			$transition = new \OCA\EWS\Components\EWS\Type\RecurringDayTransitionType();
			$transition->To = new \OCA\EWS\Components\EWS\Type\TransitionTargetType();
			$transition->To->_ = 'DL';
			$transition->To->Kind = 'Period';
			$transition->Month = $zone->DaylightStartMonth;
			$transition->Occurrence = $zone->DaylightStartWeeK;
			$transition->DayOfWeek = $zone->DaylightStartDay;
			$transition->TimeOffset = $zone->DaylightStartTime;
			$group->RecurringDayTransition[] = $transition;

			$o->TransitionsGroups->TransitionsGroup[] = $group;

			$o->Transitions = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsType();
			$o->Transitions->Transition = new \OCA\EWS\Components\EWS\Type\TransitionType();
			$o->Transitions->Transition->To = new \OCA\EWS\Components\EWS\Type\TransitionTargetType();
			$o->Transitions->Transition->To->_ = 0;
			$o->Transitions->Transition->To->Kind = 'Group';
		}

		/*
		$o->Transitions->AbsoluteDateTransition = new \OCA\EWS\Components\EWS\Type\AbsoluteDateTransitionType();
		$o->Transitions->AbsoluteDateTransition->To = new \OCA\EWS\Components\EWS\Type\TransitionTargetType();
		$o->Transitions->AbsoluteDateTransition->To->_ = 1;
		$o->Transitions->AbsoluteDateTransition->To->Kind = 'Group';
		$o->Transitions->AbsoluteDateTransition->DateTime = '2007-01-01T00:00:00';
		*/
        // return object
        return $o;
    }

	/**
     * convert remote CalendarItemType object to EventObject
     * 
	 * @param CalendarItemType $data - item as CalendarItemType object
	 * 
	 * @return EventObject item as EventObject
	 */
	public function toEventObject(CalendarItemType $data) : EventObject {
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
            $o->UUID = $data->UID;
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
		// Start Date/Time
		if (!empty($data->Start)) {
			$o->StartsOn = new DateTime($data->Start);
		}
		// Start Time Zone
        if (!empty($data->StartTimeZone)) {
			$o->StartsTZ = $this->fromTimeZone($data->StartTimeZone);
        }
		// End Date/Time
        if (!empty($data->End)) {
            $o->EndsOn = new DateTime($data->End);
        }
		// End Time Zone
        if (!empty($data->EndTimeZone)) {
        	$o->EndsTZ = $this->fromTimeZone($data->EndTimeZone);
        }
		// All Day Event
		if(isset($data->IsAllDayEvent) && $data->IsAllDayEvent == true) {
			$o->StartsOn->setTime(0,0,0,0);
			$o->EndsOn->setTime(0,0,0,0);
		}
		// Time Zone
        if (!empty($data->TimeZone)) {
        	$o->TimeZone = $this->fromTimeZone($data->TimeZone);
			if (!isset($o->StartTZ)) { $o->StartTZ = $o->TimeZone; }
			if (!isset($o->EndTZ)) { $o->EndTZ = $o->TimeZone; }
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
		if (isset($data->Attachments)) {
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
						$o->UUID = $entry->Value;
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
	public function fromTimeZone(string $name) : ?DateTimeZone {
		
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
	public function toTimeZone(DateTimeZone $zone) : ?string {

		// convert DateTimeZone object to EWS time zone name
		return \OCA\EWS\Utile\TimeZoneEWS::fromDateTimeZone($zone);

	}

	private function fromDaysOfWeek(string $data, bool $group = false ) : array {
		$days = array(
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
		// split data in to array
		$data = explode(' ', $data);
		// check if days match any group patterns
		if ($group && count($data) == 1) {
			$groups = array(
				'Day' => array(1,2,3,4,5,6,7),
				'Weekday' => array(1,2,3,4,5),
				'WeekendDay' => array(6,7)
			);
			if (isset($groups[$data[0]])) {
				return $groups[$data[0]];
			}
		}
		// convert values
		foreach ($data as $key => $entry) {
			if (isset($days[$entry])) {
				$data[$key] = $days[$entry];
			}
		}
		// return converted array
		return $data;
	}

	private function toDaysOfWeek(array $data, bool $group = false) : string {
		
		$days = array(
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

		// check if days match any group patterns
		if ($group) {
			$groups = array(
				'Day' => array(1,2,3,4,5,6,7),
				'Weekday' => array(1,2,3,4,5),
				'WeekendDay' => array(6,7)
			);
			sort($data);
			foreach ($groups as $key => $entry) {
				if ($data == $entry) {
					return $key;
				}
			}
		}

        // convert values
        foreach ($data as $key => $entry) {
            if (isset($days[$entry])) {
                $data[$key] = $days[$entry];
            }
        }
        // split data in to array
        $data = implode(' ', $data);
        // return converted string
        return $data;
	}

	private function fromDaysOfMonth(string $data) : array {
		// split data in to array
		$data = explode(' ', $data);
		// return converted array
		return $data;
	}

	private function toDaysOfMonth(array $data) : string {
        // combine data to string
        $data = implode(' ', $data);
        // return converted string
        return $data;
	}

	private function fromWeekOfMonth(string $data) : array {
		$weeks = array(
			'First' => 1,
			'Second' => 2,
			'Third' => 3,
			'Fourth' => 4,
			'Last' => -1
		);
		// split data in to array
		$data = explode(' ', $data);
		// convert values
		foreach ($data as $key => $entry) {
			if (isset($weeks[$entry])) {
				$data[$key] = $weeks[$entry];
			}
		}
		// return converted array
		return $data;
	}

	private function toWeekOfMonth(array $data) : string {
		$weeks = array(
			1 => 'First',
			2 => 'Second',
			3 => 'Third',
			4 => 'Fourth',
			-1 => 'Last',
			-2 => 'Fourth'
		);
		// convert values
        foreach ($data as $key => $entry) {
            if (isset($weeks[$entry])) {
                $data[$key] = $weeks[$entry];
            }
        }
        // split data in to array
        $data = implode(',', $data);
        // return converted string
        return $data;
	}

	private function fromMonthOfYear(string $data) : array {
		$months = array(
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
		// split data in to array
		$data = explode(' ', $data);
		// convert values
		foreach ($data as $key => $entry) {
			if (isset($months[$entry])) {
				$data[$key] = $months[$entry];
			}
		}
		// return converted array
		return $data;
	}

	private function toMonthOfYear(array $data) : string {
		$months = array(
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
		// convert values
        foreach ($data as $key => $entry) {
            if (isset($months[$entry])) {
                $data[$key] = $months[$entry];
            }
        }
        // split data in to array
        $data = implode(',', $data);
        // return converted string
        return $data;
	}

	private function fromSensitivity(?string $level) : ?int {
		
		$levels = array(
			'Normal' => 0,
			'Personal' => 1,
			'Private' => 2,
			'Confidential' => 3
		);
		if (isset($levels[$level])) {
			// return converted value
			return $levels[$level];
		} else {
			return 0;
		}
		
	}

	private function toSensitivity(?int $level) : ?string {
		
		$levels = array(
			0 => 'Normal',
			1 => 'Personal',
			2 => 'Private',
			3 => 'Confidential'
		);
		if (isset($levels[$level])) {
			// return converted value
			return $levels[$level];
		} else {
			return 'Normal';
		}

	}

	private function fromImportance(?string $level) : ?int {
		
		$levels = array(
			'Low' => 0,
			'Normal' => 1,
			'High' => 2
		);
		if (isset($levels[$level])) {
			// return converted value
			return $levels[$level];
		} else {
			return 1;
		}
		
	}

	private function toImportance(?int $level) : ?string {
		
		$levels = array(
			0 => 'Low',
			1 => 'Normal',
			2 => 'High'
		);
		if (isset($levels[$level])) {
			// return converted value
			return $levels[$level];
		} else {
			return 'Normal';
		}

	}

	private function fromAttendeeResponse(?string $response) : ?string {
		
		$responses = array(
			'Accept' => 'A',
			'Decline' => 'D',
			'Tentative' => 'T',
			'Organizer' => 'O',
			'Unknown' => 'U',
			'NoResponseReceived' => 'N'
		);
		if (isset($responses[$response])) {
			// return converted value
			return $responses[$response];
		} else {
			return 'N';
		}
		
	}

	private function toAttendeeResponse(?string $response) : ?string {
		
		$responses = array(
			'A' => 'Accept',
			'D' => 'Decline',
			'T' => 'Tentative',
			'O' => 'Organizer',
			'U' => 'Unknown',
			'N' => 'NoResponseReceived'
		);
		if (isset($responses[$response])) {
			// return converted value
			return $responses[$response];
		} else {
			return 'NoResponseReceived';
		}

	}

	private function constructDefaultCollectionProperties() : object {

		// construct properties array
		if (!isset($this->DefaultCollectionProperties)) {
			$p = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:FolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:FolderClass');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:ParentFolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:DisplayName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:TotalCount');


			$this->DefaultCollectionProperties = $p;
		}

		return $this->DefaultCollectionProperties;

	}

	private function constructDefaultItemProperties() : object {

		// construct properties array
		if (!isset($this->DefaultItemProperties)) {
			$p = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ItemId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:UID');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ParentFolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeCreated');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeSent');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:LastModifiedTime');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:TimeZone');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:Start');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:StartTimeZone');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:End');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:EndTimeZone');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Subject');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Body');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:Location');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:LegacyFreeBusyStatus');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Importance');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Sensitivity');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Categories');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:Organizer');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:RequiredAttendees');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:OptionalAttendees');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ReminderIsSet');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ReminderMinutesBeforeStart');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:Recurrence');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:ModifiedOccurrences');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:DeletedOccurrences');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Attachments');

			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:UniqueBody');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:AppointmentState');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('calendar:Resources');

			$this->DefaultItemProperties = $p;
		}

		return $this->DefaultItemProperties;

	}
}
