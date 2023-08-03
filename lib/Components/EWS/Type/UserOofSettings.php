<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserOofSettings.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the Out of Office (OOF) settings.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserOofSettings extends Type
{
    /**
     * Contains the duration for which the OOF status is enabled if the OofState
     * element is set to Scheduled.
     *
     * If the OofState element is set to Enabled or Disabled, the value of this
     * element is ignored.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\Duration
     */
    public $Duration;

    /**
     * Contains a value that determines to whom external OOF messages are sent.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ExternalAudience
     */
    public $ExternalAudience;

    /**
     * Contains the OOF response sent to addresses outside the recipient's
     * domain or trusted domains.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyBody
     */
    public $ExternalReply;

    /**
     * Contains the OOF response sent to other users in the user's domain or
     * trusted domain.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyBody
     */
    public $InternalReply;

    /**
     * Contains the user's OOF state.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\OofState
     */
    public $OofState;
}
