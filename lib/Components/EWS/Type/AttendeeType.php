<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AttendeeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents attendees and resources for a meeting.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttendeeType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $Mailbox = null, string $Response = null)
    {
        $this->Mailbox = $Mailbox;
        $this->ResponseType = $Response;
    }

    /**
     * Represents the date and time of the latest response that is received.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastResponseTime;

    /**
     * Identifies a fully resolved e-mail address.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;

    /**
     * Represents the type of recipient response that is received for a meeting.
     *
     * This property is only relevant to a meeting organizer's calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ResponseTypeType
     */
    public $ResponseType;
}
