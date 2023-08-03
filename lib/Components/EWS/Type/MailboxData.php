<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxData.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents an individual mailbox user and options for the type of data to be
 * returned about the mailbox user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxData extends Type
{
    /**
     * Represents the type of attendee identified in the Email property.
     *
     * This is used in requests for meeting suggestions.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MeetingAttendeeType
     */
    public $AttendeeType;

    /**
     * Represents the mailbox user for a GetUserAvailability query.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Email;

    /**
     * Specifies whether to return suggested times for calendar times that
     * conflict among the attendees.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $ExcludeConflicts;
}
