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
use OCA\EWS\Components\EWS\Type\TaskType;
use OCA\EWS\Objects\TaskCollectionObject;
use OCA\EWS\Objects\TaskObject;
use OCA\EWS\Objects\TaskAttachmentObject;

class RemoteTasksService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var RemoteCommonService
	 */
	private $RemoteCommonService;
	/**
	 * @var EWSClient
	 */
	private ?EWSClient $DataStore = null;
    /**
	 * @var Object
	 */
	private $Configuration;
	/**
	 * @var DateTimeZone
	 */
	private ?DateTimeZone $SystemTimeZone = null;
    /**
	 * @var DateTimeZone
	 */
	private ?DateTimeZone $UserTimeZone = null;
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
		$cr = $this->RemoteCommonService->fetchFoldersByType($this->DataStore, 'IPF.Task', 'I', $this->constructDefaultCollectionProperties(), $source);
		// process response
		$cl = array();
		if (isset($cr)) {
			foreach ($cr->TasksFolder as $folder) {
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
	 * @return TaskCollectionObject
	 */
	public function fetchCollection(string $cid): ?TaskCollectionObject {

        // execute command
		$cr = $this->RemoteCommonService->fetchFolder($this->DataStore, $cid, false, 'I', $this->constructDefaultCollectionProperties());
		// process response
		if (isset($cr) && (count($cr->TasksFolder) > 0)) {
		    $ec = new TaskCollectionObject(
				$cr->TasksFolder[0]->FolderId->Id,
				$cr->TasksFolder[0]->DisplayName,
				$cr->TasksFolder[0]->FolderId->ChangeKey,
				$cr->TasksFolder[0]->TotalCount
			);
			if (isset($cr->TasksFolder[0]->ParentFolderId->Id)) {
				$ec->AffiliationId = $cr->TasksFolder[0]->ParentFolderId->Id;
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
	 * @return TaskCollectionObject
	 */
	public function createCollection(string $cid, string $name, bool $ctype = false): ?TaskCollectionObject {
        
		// construct command object
		$ec = new \OCA\EWS\Components\EWS\Type\TasksFolderType();
		$ec->DisplayName = $name;
		// execute command
		$cr = $this->RemoteCommonService->createFolder($this->DataStore, $cid, $ec, $ctype);
        // process response
		if (isset($cr) && (count($cr->TasksFolder) > 0)) {
		    return new TaskCollectionObject(
				$cr->TasksFolder[0]->FolderId->Id,
				$name,
				$cr->TasksFolder[0]->FolderId->ChangeKey
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

        // define place holders
        $data = array();
        $offset = 0;

        do {
            // execute command
            $ro = $this->RemoteCommonService->fetchItemsIds($this->DataStore, $cid, $ctype, $offset);
            // validate response object
            if (isset($ro) && count($ro->Task) > 0) {
                foreach ($ro->Task as $entry) {
                    if ($entry->ExtendedProperty) {
                        $data[] = array('ID'=>$entry->ItemId->Id, 'UUID'=>$entry->ExtendedProperty[0]->Value);
                    }
                }
                $offset += count($ro->Task);
            }
        }
        while (isset($ro) && count($ro->Task) > 0);
        // return
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
	public function fetchCollectionItem(string $iid): ?TaskObject {
        
		// construct identification object
        $io = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);
		// execute command
		$ro = $this->RemoteCommonService->fetchItem($this->DataStore, array($io), 'D', $this->constructDefaultItemProperties());
        // validate response
		if (isset($ro->Task)) {
			// convert to task object
            $to = $this->toTaskObject($ro->Task[0]);
            // retrieve attachment(s) from remote data store
			if (count($to->Attachments) > 0) {
				$to->Attachments = $this->fetchCollectionItemAttachment(array_column($to->Attachments, 'Id'));
			}
            // return object
		    return $to;
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
	 * @return TaskObject
	 */
	public function fetchCollectionItemByUUID(string $cid, string $uuid): ?TaskObject {

        // retrieve properties for a specific collection item
		$ro = $this->RemoteCommonService->findItemByUUID($this->DataStore, $cid, $uuid, false, 'D', $this->constructDefaultItemProperties());
		// validate response
		if (isset($ro->Task)) {
			// convert to task object
            $to = $this->toTaskObject($ro->Task[0]);
            // retrieve attachment(s) from remote data store
			if (count($to->Attachments) > 0) {
				$to->Attachments = $this->fetchCollectionItemAttachment(array_column($to->Attachments, 'Id'));
			}
            // return object
		    return $to;
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
     * @param TaskObject $so - Source Data
	 * 
	 * @return TaskObject
	 */
	public function createCollectionItem(string $cid, TaskObject $so): ?TaskObject {

        // construct request object
        $ro = new TaskType();
		// UUID
		if (!empty($so->UUID)) {
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
			$ro->StartDate = $dt->format('Y-m-d\\TH:i:s\Z');
			// destory temporaty variable(s)
			unset($dt);
        }
		// Due Date/Time
		if (!empty($so->DueOn)) {
			// ews wants the date time in UTC
			// clone end date
			$dt = clone $so->DueOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time attribute
			$ro->DueDate = $dt->format('Y-m-d\\TH:i:s\Z');
			// destory temporaty variable(s)
			unset($dt);
		}
		// Conpleted Date/Time
		if (!empty($so->CompletedOn)) {
			// ews wants the date time in UTC
			// clone end date
			$dt = clone $so->CompletedOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time attribute
			$ro->CompleteDate = $dt->format('Y-m-d\\TH:i:s\Z');
			// destory temporaty variable(s)
			unset($dt);
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
		// Progress
		if (!empty($so->Progress)) {
			$ro->PercentComplete = $so->Progress;
		}
		// Status
		if (!empty($so->Status)) {
			$ro->Status = $this->toStatus($so->Status);
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
		// Notifications
		if (count($so->Notifications) > 0) {
			$ro->ReminderIsSet  = 'true';
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil(($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp() / 60));
				$ro->ReminderIsSet = 'true';
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
				$ro->ReminderIsSet = 'true';
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
        if ($rs->Task[0]->ItemId) {
			$to = clone $so;
			$to->ID = $rs->Task[0]->ItemId->Id;
			$to->CID = $cid;
			$to->State = $rs->Task[0]->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($to->Attachments) > 0) {
				// create attachments in remote data store
				$to->Attachments = $this->createCollectionItemAttachment($to->ID, $to->Attachments);
				$to->State = $to->Attachments[0]->AffiliateState;
			}
            return $to;
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
     * @param TaskObject $so - Source Data
	 * 
	 * @return TaskObject
	 */
	public function updateCollectionItem(string $cid, string $iid, TaskObject $so): ?TaskObject {

        // request modifications array
        $rm = array();
        // request deletions array
        $rd = array();
		// UUID
        if (!empty($so->UUID)) {
            $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UUID);
        }
        else {
            $rd[] = $this->deleteFieldExtendedByName('PublicStrings', 'DAV:uid', 'String');
        }
		
        // Starts On
        if (!empty($so->StartsOn)) {
			// clone start date
			$dt = clone $so->StartsOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct start time attribute
			$rm[] = $this->updateFieldUnindexed('task:StartDate', 'StartDate', $dt->format('Y-m-d\\TH:i:s\Z'));
			// destroy temporary variable(s)
			unset($dt);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('task:StartDate');
        }
		// Due On
        if (!empty($so->DueOn)) {
			// clone end date
			$dt = clone $so->DueOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time property
			$rm[] = $this->updateFieldUnindexed('task:DueDate', 'DueDate', $dt->format('Y-m-d\\TH:i:s\Z'));
			// destroy temporary variable(s)
			unset($dt);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('task:DueDate');
        }
		// Completed On
        if (!empty($so->CompletedOn)) {
			// clone end date
			$dt = clone $so->CompletedOn;
			// change timezone on cloned date
			$dt->setTimezone(new DateTimeZone('UTC'));
			// construct end time property
			$rm[] = $this->updateFieldUnindexed('task:CompleteDate', 'CompleteDate', $dt->format('Y-m-d\\TH:i:s\Z'));
			// destroy temporary variable(s)
			unset($dt);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('task:CompleteDate');
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
		// Progress
		if (!empty($so->Progress)) {
			$rm[] = $this->updateFieldUnindexed('task:PercentComplete', 'PercentComplete', $so->Progress);
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('task:PercentComplete');
		}
		// Status
		if (!empty($so->Status)) {
			$rm[] = $this->updateFieldUnindexed('task:Status', 'Status', $this->toStatus($so->Status));
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('task:Status');
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
		// Notification(s)
		if (count($so->Notifications) > 0) {
			$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', 'true');
			if ($so->Notifications[0]->Type == 'D' && $so->Notifications[0]->Pattern == 'A') {
				$t = ceil(($so->StartsOn->getTimestamp() - $so->Notifications[0]->When->getTimestamp() / 60));
				$rm[] = $this->updateFieldUnindexed('item:ReminderMinutesBeforeStart', 'ReminderMinutesBeforeStart', $t);
				$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', 'ture');
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
				$rm[] = $this->updateFieldUnindexed('item:ReminderMinutesBeforeStart', 'ReminderMinutesBeforeStart',(string) $t);
				$rm[] = $this->updateFieldUnindexed('item:ReminderIsSet', 'ReminderIsSet', 'ture');
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
			// Daily Task
			if ($so->Occurrence->Precision == 'D') {
				$f->DailyRecurrence = new \OCA\EWS\Components\EWS\Type\DailyRecurrencePatternType();
				if (!empty($so->Occurrence->Interval)) {
					$f->DailyRecurrence->Interval = $so->Occurrence->Interval;
				}
				else {
					$f->DailyRecurrence->Interval = '1';
				}
			}
			// Weekly Task
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
			// Monthly Task
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
			// Yearly Task
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
			
			$rm[] = $this->updateFieldUnindexed('task:Recurrence', 'Recurrence', $f);
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('task:Recurrence');
		}
        // execute command
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, $rd);
		// process response
        if ($rs->Task[0]) {
			$to = clone $so;
			$to->ID = $rs->Task[0]->ItemId->Id;
			$to->CID = $cid;
			$to->State = $rs->Task[0]->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($to->Attachments) > 0) {
				// create attachments in remote data store
				$to->Attachments = $this->createCollectionItemAttachment($to->ID, $to->Attachments);
				$to->State = $to->Attachments[0]->AffiliateState;
			}
            return $to;
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
     * @param string $cid - Collection Item UUID
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItemUUID(string $cid, string $iid, string $uuid): ?object {
		// request modifications array
        $rm = array();
        // construct update command object
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, null);
        // return response
        if ($rs->Task[0]) {
            return (object) array('ID' => $rs->Task[0]->ItemId->Id, 'UID' => $uuid, 'State' => $rs->Task[0]->ItemId->ChangeKey);
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
     * @since Release 1.0.0
     * 
     * @param string $aid - Attachment ID
	 * 
	 * @return TaskAttachmentObject
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
				$rc[] = new TaskAttachmentObject(
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
     * @param array $sc - Collection of TaskAttachmentObject(S)
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
	private function constructDefaultCollectionProperties(): object {

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

	/**
     * construct collection of default remote object properties 
     * 
     * @since Release 1.0.0
	 * 
	 * @return object
	 */
	private function constructDefaultItemProperties(): object {

		// construct properties array
		if (!isset($this->DefaultItemProperties)) {
			$p = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ItemId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ParentFolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeCreated');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeSent');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:LastModifiedTime');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Subject');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Body');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Sensitivity');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Importance');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Categories');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ReminderDueBy');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ReminderIsSet');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ReminderMinutesBeforeStart');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Attachments');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:StartDate');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:DueDate');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:CompleteDate');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:Status');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:StatusDescription');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:PercentComplete');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:ActualWork');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:TotalWork');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('task:Recurrence');

			$this->DefaultItemProperties = $p;
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
        $o->Task = new \OCA\EWS\Components\EWS\Type\TaskType();
        $o->Task->$name = $value;
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
        $o->Task = new \OCA\EWS\Components\EWS\Type\TaskType();
        $o->Task->$name = $dictionary;
        $o->Task->$name->Entry = $entry;
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
        $o->Task = new \OCA\EWS\Components\EWS\Type\TaskType();
        $o->Task->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
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
        $o->Task = new \OCA\EWS\Components\EWS\Type\TaskType();
        $o->Task->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
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
     * convert remote TaskType object to TaskObject
     * 
     * @since Release 1.0.0
     * 
	 * @param TaskType $data - item as TaskType object
	 * 
	 * @return TaskObject item as TaskObject
	 */
	public function toTaskObject(TaskType $data): TaskObject {
		
		// create object
		$o = new TaskObject();
		// Origin
		$o->Origin = 'R';
        // ID / State
        if (isset($data->ItemId)) {
            $o->ID = $data->ItemId->Id;
            $o->State = $data->ItemId->ChangeKey;
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
		if (!empty($data->StartDate)) {
			$o->StartsOn = new DateTime($data->StartDate);
		}
		// Due Date/Time
        if (!empty($data->DueDate)) {
            $o->DueOn = new DateTime($data->DueDate);
        }
		// Completed Date/Time
        if (!empty($data->CompleteDate)) {
            $o->CompletedOn = new DateTime($data->CompleteDate);
        }
		// Label
        if (!empty($data->Subject)) {
            $o->Label = $data->Subject;
        }
		// Notes
		if (!empty($data->Body)) {
			$o->Notes = $data->Body->_;
		}
		// Progress
        if (!empty($data->PercentComplete)) {
            $o->Progress = $data->PercentComplete;
        }
		// Status
        if (!empty($data->Status)) {
            $o->Status = $this->fromStatus($data->Status);
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
     * convert remote status to task object status
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $status - remote status value
	 * 
	 * @return string task object status value
	 */
	private function fromStatus(?string $status): string {
		
		// status conversion reference
		$statuses = array(
			'NotStarted' => 'N',
			'InProgress' => 'P',
			'Completed' => 'C',
			'WaitingOnOthers' => 'W',
			'Deferred' => 'D',
		);
		// evaluate if status value exists
		if (isset($statuses[$status])) {
			// return converted status value
			return $statuses[$status];
		} else {
			// return default status value
			return 'N';
		}
		
	}

	/**
     * convert task object status to remote status
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $status - task object status value
	 * 
	 * @return string remote status value
	 */
	private function toStatus(?string $status): string {
		
		// sensitivity conversion reference
		$statuses = array(
			'N' => 'NotStarted',
			'P' => 'InProgress',
			'C' => 'Completed',
			'W' => 'WaitingOnOthers',
			'D' => 'Deferred'
		);
		// evaluate if sensitivity value exists
		if (isset($statuses[$status])) {
			// return converted sensitivity value
			return $statuses[$status];
		} else {
			// return default sensitivity value
			return 'NotStarted';
		}

	}

	/**
     * convert remote days of the week to task object days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $days - remote days of the week values(s)
	 * @param bool $group - flag to check if days are grouped
	 * 
	 * @return array task object days of the week values(s)
	 */
	private function fromDaysOfWeek(string $days, bool $group = false ): array {

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
     * convert task object days of the week to remote days of the week
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - task object days of the week values(s)
	 * @param bool $group - flag to check if days can be grouped 
	 * 
	 * @return string remote days of the week values(s)
	 */
	private function toDaysOfWeek(array $days, bool $group = false): string {
		
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
     * convert remote days of the month to task object days of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $days - remote days of the month values(s)
	 * 
	 * @return array task object days of the month values(s)
	 */
	private function fromDaysOfMonth(string $days): array {

		// convert days to array
		$days = explode(' ', $days);
		// return converted days
		return $days;

	}

	/**
     * convert task object days of the month to remote days of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $days - task object days of the month values(s)
	 * 
	 * @return string remote days of the month values(s)
	 */
	private function toDaysOfMonth(array $days): string {

        // convert days to string
        $days = implode(' ', $days);
        // return converted days
        return $days;

	}

	/**
     * convert remote week of the month to task object week of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $weeks - remote week of the month values(s)
	 * 
	 * @return array task object week of the month values(s)
	 */
	private function fromWeekOfMonth(string $weeks): array {

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
     * convert task object week of the month to remote week of the month
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $weeks - task object week of the month values(s)
	 * 
	 * @return string remote week of the month values(s)
	 */
	private function toWeekOfMonth(array $weeks): string {

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
     * convert remote month of the year to task object month of the year
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $months - remote month of the year values(s)
	 * 
	 * @return array task object month of the year values(s)
	 */
	private function fromMonthOfYear(string $months): array {

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
     * convert task object month of the year to remote month of the year
	 * 
     * @since Release 1.0.0
     * 
	 * @param array $weeks - task object month of the year values(s)
	 * 
	 * @return string remote month of the year values(s)
	 */
	private function toMonthOfYear(array $months): string {

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
     * convert remote sensitivity to task object sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - remote sensitivity value
	 * 
	 * @return int task object sensitivity value
	 */
	private function fromSensitivity(?string $level): int {
		
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
     * convert task object sensitivity to remote sensitivity
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - task object sensitivity value
	 * 
	 * @return string remote sensitivity value
	 */
	private function toSensitivity(?int $level): string {
		
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
     * convert remote importance to task object priority
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $level - remote importance value
	 * 
	 * @return int task object priority value
	 */
	private function fromImportance(?string $level): int {
		
		// EWS: 0 = low, 1 = normal (default), 2 = high
		// VTODO: 0 = undefined, 1-3 = high, 4-6 = normal, 7-9 = low

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
     * convert task object priority to remote importance
	 * 
     * @since Release 1.0.0
     * 
	 * @param int $level - task object priority value
	 * 
	 * @return string remote importance value
	 */
	private function toImportance(?int $level): string {

		// EWS: 0 = low, 1 = normal (default), 2 = high
		// VTODO: 0 = undefined, 1-3 = high, 4-6 = normal, 7-9 = low

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
     * convert remote attendee response to task object response
	 * 
     * @since Release 1.0.0
     * 
	 * @param sting $response - remote attendee response value
	 * 
	 * @return string task object attendee response value
	 */
	private function fromAttendeeResponse(?string $response): string {
		
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
     * convert task object attendee response to remote attendee response
	 * 
     * @since Release 1.0.0
     * 
	 * @param string $response - task object attendee response value
	 * 
	 * @return string remote attendee response value
	 */
	private function toAttendeeResponse(?string $response): string {
		
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
