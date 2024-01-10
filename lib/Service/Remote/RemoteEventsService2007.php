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

class RemoteEventsService2007 extends RemoteEventsService {

	public function __construct (string $appName,
								LoggerInterface $logger,
								RemoteCommonService $RemoteCommonService) {
		parent::__construct($appName, $logger, $RemoteCommonService);
		$this->logger = $logger;
		$this->RemoteCommonService = $RemoteCommonService;
	}

	public function configure($configuration, EWSClient $DataStore) : void {
		
		parent::configure($configuration, $DataStore);

		// assign configuration
		$this->Configuration = $configuration;
		// assign remote data store
		$this->DataStore = $DataStore;
		// assign timezones
		$this->SystemTimeZone = $configuration->SystemTimeZone;
		$this->UserTimeZone = $configuration->UserTimeZone;
		
	}

	/**
     * construct collection of default remote object properties 
     * 
     * @since Release 1.0.15
	 * 
	 * @return object
	 */
	public function constructDefaultItemProperties(): object {

		// evaluate if default item properties collection exisits
		if (!isset($this->DefaultItemProperties)) {
			// unindexed property names collection
			$_properties = [
				'item:ItemId',
				'item:ParentFolderId',
				'item:DateTimeCreated',
				'item:DateTimeSent',
				'item:LastModifiedTime',
				'item:Subject',
				'item:Body',
				'item:Importance',
				'item:Sensitivity',
				'item:Categories',
				'item:ReminderIsSet',
				'item:ReminderMinutesBeforeStart',
				'item:Attachments',
				'calendar:UID',
				'calendar:TimeZone',
				'calendar:Start',
				'calendar:End',
				'calendar:Location',
				'calendar:LegacyFreeBusyStatus',
				'calendar:Organizer',
				'calendar:RequiredAttendees',
				'calendar:OptionalAttendees',
				'calendar:Recurrence',
				'calendar:ModifiedOccurrences',
				'calendar:DeletedOccurrences',
				'calendar:AppointmentState',
				'calendar:Resources',
				'calendar:IsAllDayEvent',
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
     * create collection item in remote storage
     * 
     * @since Release 1.0.15
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
            $ro->UID = $this->toUID($so->UUID, 'SH');
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
		}
		// All Day Event
		if(!empty($so->Span) && $so->Span == 'F') {
			$ro->IsAllDayEvent = true;
		}
		else {
			$ro->IsAllDayEvent = false;
		}
		// evaluate if global time zone present 
		if ($so->TimeZone instanceof \DateTimeZone) {
			$tz = $so->TimeZone;
		}
		// evaluate if start time zone is present
		elseif ($so->StartsTZ instanceof \DateTimeZone) {
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
		if (isset($tz)) {
			// convert time zone name
			$tz = $this->toTimeZone($tz);
			// construct time zone
			$ro->MeetingTimeZone = $this->constructTimeZone($tz);
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
     * @since Release 1.0.15
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
	 * @param string $istate - Collection Item State
     * @param EventObject $so - Source Data
	 * 
	 * @return EventObject
	 */
	public function updateCollectionItem(string $cid, string $iid,  string $istate, EventObject $so): ?EventObject {

        // request modifications array
        $rm = array();
        // request deletions array
        $rd = array();
		// UUID
        if (!empty($so->UUID)) {
			$rm[] = $this->updateFieldUnindexed('calendar:UID', 'UID', $this->toUID($so->UUID, 'SH'));
            $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UUID);
        }
        else {
			$rd[] = $this->deleteFieldUnindexed('calendar:UID');
            $rd[] = $this->deleteFieldExtendedByName('PublicStrings', 'DAV:uid', 'String');
        }
        // Starts On
        if (!empty($so->StartsOn)) {
			// clone start date
			$dt = clone $so->StartsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct start time attribute
			$rm[] = $this->updateFieldUnindexed('calendar:Start', 'Start', $dt->format('Y-m-d\\TH:i:s\Z'));
			// destroy temporary variables
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
			// destroy temporary variables
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
		// TimeZone / MeetingTimeZone
		// evaluate if global time zone present 
		if ($so->TimeZone instanceof \DateTimeZone) {
			$tz = $so->TimeZone;
		}
		// evaluate if start time zone is present
		elseif ($so->StartsTZ instanceof \DateTimeZone) {
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
		if (isset($tz)) {
			// convert time zone name
			$tz = $this->toTimeZone($tz);
			// construct time zone
			$rm[] = $this->updateFieldUnindexed(
				'calendar:MeetingTimeZone',
				'MeetingTimeZone',
				$this->constructTimeZone($tz)
			);
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
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, $istate, null, $rm, $rd);
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
        $rm[] = $this->updateFieldUnindexed('calendar:UID', 'UID', $this->toUID($uuid, 'SH'));
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, $istate, null, $rm, null);
        // return response
        if ($rs->CalendarItem[0]) {
            return (object) array('ID' => $rs->CalendarItem[0]->ItemId->Id, 'UID' => $uuid, 'State' => $rs->CalendarItem[0]->ItemId->ChangeKey);
        } else {
            return null;
        }
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
			$co->Name = $entry->Name;
			$co->ContentId = $entry->Name;
			$co->ContentType = $entry->Type;
			
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
     * Calculates Duration from  
     * 
     * @since Release 1.0.15
     * 
     * @param DataTime $Start
	 * @param DataTime $End
     * 
     * @return string 
     */ 
	public function constructDuration(DateTime $Start, DateTime $End): ?string {

		return $Start->diff($End)->format('P%dD%hH%mM');

	}

	/**
     * Converts time zone name to EWS (Microsoft/Windows) time zone object
     * 
     * @since Release 1.0.15
     * 
     * @param string $name
     * 
     * @return object valid EWS time zone object on success, or null on failure
     */ 
	public function constructTimeZone(string $name): object {

		// retrive time zone properties
		$tz = \OCA\EWS\Utile\TimeZoneEWS::find($name);
		$tzname = $tz->Id;
		$tzoffset = $tz->Name;
		// calculate offset from zone description
		// this is crude but the simplest way to add support for a obsolete system
		preg_match_all(
			'/^\(UTC(?<Symbol>[\+\-])(?<Hours>\d{2})\:(?<Minutes>\d{2})\)/',
			$tzoffset,
			$match
		);
		// retrieve matches
		$symbol = $match['Symbol'][0];
		$hours = $match['Hours'][0];
		$minutes = $match['Minutes'][0];
		$tzoffset = ($symbol == '+') ? '-PT' . $hours . 'H' . $minutes . 'M' : 'PT' . $hours . 'H' . $minutes . 'M';
		// create and return time zone object
		return new \OCA\EWS\Components\EWS\Type\TimeZoneType($tzname);

	}

}
