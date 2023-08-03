<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MessageTrackingReportType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single message that is returned in a GetMessageTrackingReport
 * operation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MessageTrackingReportType extends Type
{
    /**
     * Contains a list of the recipients of the e-mail message.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $OriginalRecipients;

    /**
     * Contains a list of one or more tracking properties.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;

    /**
     * Contains contact information for the alleged sender of an e-mail message.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $PurportedSender;

    /**
     * Contains a list of one or more tracking events for the recipients.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientTrackingEventType
     */
    public $RecipientTrackingEvents;

    /**
     * Contains contact information for the sender of the e-mail message.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Sender;

    /**
     * Contains the subject of the e-mail message.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $Subject;

    /**
     * Contains the time of day that the e-mail message was submitted.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $SubmitTime;
}
