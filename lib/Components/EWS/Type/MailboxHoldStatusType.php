<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxHoldStatusType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the hold status of a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxHoldStatusType extends Type
{
    /**
     * Specifies additional information about the hold status of a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $AdditionalInfo;

    /**
     * Contains the identifier for a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Mailbox;

    /**
     * Specifies the hold status for a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\HoldStatusType
     */
    public $Status;
}
