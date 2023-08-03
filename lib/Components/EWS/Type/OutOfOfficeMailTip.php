<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\OutOfOfficeMailTip.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the response message and a duration time for sending the response
 * message.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class OutOfOfficeMailTip extends Type
{
    /**
     * Contains the duration that the OOF status is enabled if the OofState
     * element is set to Scheduled.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\Duration
     */
    public $Duration;

    /**
     * Contains an Out of Office (OOF) message and the language used for the
     * message.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyBody
     */
    public $ReplyBody;
}
