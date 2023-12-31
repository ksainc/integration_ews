<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
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

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a meeting cancellation in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MeetingRequestMessageType extends MeetingMessageType
{
    /**
     * Represents the total number of calendar items that are adjacent to a
     * meeting time.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $AdjacentMeetingCount;

    /**
     * Describes all calendar items that are adjacent to a meeting time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAllItemsType
     */
    public $AdjacentMeetings;

    /**
     * Represents whether a new meeting time can be proposed for a meeting.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $AllowNewTimeProposal;

    /**
     * Represents the date and time when an attendee replied to a meeting
     * request.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $AppointmentReplyTime;

    /**
     * Specifies the sequence number of a version of an appointment.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $AppointmentSequenceNumber;

    /**
     * Specifies the status of the appointment.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $AppointmentState;

    /**
     * Represents the occurrence type of a calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarItemTypeType
     */
    public $CalendarItemType;

    /**
     * Describes the type of conferencing that is performed with a calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ConferenceType
     */
    public $ConferenceType;

    /**
     * Represents the number of meetings that conflict with the meeting request.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $ConflictingMeetingCount;

    /**
     * Identifies all items that conflict with a meeting time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAllItemsType
     */
    public $ConflictingMeetings;

    /**
     * Contains an array of deleted occurrences of a recurring calendar item.
     *
     * This element is valid if CalendarItemType has the RecurringMaster value.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfDeletedOccurrencesType
     */
    public $DeletedOccurrences;

    /**
     * Represents the duration of a calendar item.
     *
     * This property is read-only.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Duration;

    /**
     * Represents the end of a duration.
     *
     * This element only applies to a single occurrence of a calendar item.
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $End;

    /**
     * Represents the end time zone of the calendar item.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeZoneDefinitionType
     */
    public $EndTimeZone;

    /**
     * Represents the first occurrence of a recurring calendar item.
     *
     * This element is valid if CalendarItemType has the RecurringMaster value.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceInfoType
     */
    public $FirstOccurrence;

    /**
     * Represents the intended status for the calendar item that is associated
     * with the meeting request.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\LegacyFreeBusyType
     */
    public $IntendedFreeBusyStatus;

    /**
     * Indicates whether a calendar item or meeting request represents an
     * all-day event.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsAllDayEvent;

    /**
     * Indicates whether an appointment or meeting has been cancelled.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsCancelled;

    /**
     * Indicates whether the calendar item is either a meeting or appointment.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsMeeting;

    /**
     * Indicates whether the meeting is online.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsOnlineMeeting;

    /**
     * Indicates whether a calendar item is part of a recurring item.
     *
     * This element is read-only.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsRecurring;

    /**
     * Represents the last occurrence of a recurring calendar item.
     *
     * This element is valid if CalendarItemType has the RecurringMaster value.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceInfoType
     */
    public $LastOccurrence;

    /**
     * Represents the free/busy status of the calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\LegacyFreeBusyType
     */
    public $LegacyFreeBusyStatus;

    /**
     * Represents the location of a meeting or appointment.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Location;

    /**
     * Describes the type of the meeting request.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MeetingRequestTypeType
     */
    public $MeetingRequestType;

    /**
     * Indicates whether a meeting request has been sent to requested attendees.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $MeetingRequestWasSent;

    /**
     * Represents the time zone of the location where the meeting is hosted.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeZoneType
     */
    public $MeetingTimeZone;

    /**
     * Contains the URL for the meeting workspace that is linked to by the
     * calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $MeetingWorkspaceUrl;

    /**
     * Contains an array of recurring calendar item occurrences that have been
     * modified so that they are different than the recurrence master item.
     *
     * This element is valid if CalendarItemType has the RecurringMaster value.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfOccurrenceInfoType
     */
    public $ModifiedOccurrences;

    /**
     * Contains the status of or response to a calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ResponseTypeType
     */
    public $MyResponseType;

    /**
     * Specifies the URL for a Microsoft Netshow online meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $NetShowUrl;

    /**
     * Represents attendees that are not required to attend a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType
     */
    public $OptionalAttendees;

    /**
     * Represents the organizer of a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SingleRecipientType
     */
    public $Organizer;

    /**
     * Represents the original start time of a calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $OriginalStart;

    /**
     * Contains the recurrence pattern for calendar items and meeting requests.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurrenceType
     */
    public $Recurrence;

    /**
     * Represents attendees that are required to attend a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType
     */
    public $RequiredAttendees;

    /**
     * Represents a scheduled resource for a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttendeesType
     */
    public $Resources;

    /**
     * Represents the start of a calendar item.
     *
     * This element only applies to a single occurrence of a calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $Start;

    /**
     * Represents the start time zone of the calendar item.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeZoneDefinitionType
     */
    public $StartTimeZone;

    /**
     * Provides a text description of a time zone.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $TimeZone;

    /**
     * Provides a description of when a meeting occurs.
     *
     * @since Exchange 2007
     *
     * @type string
     */
    public $When;
}
