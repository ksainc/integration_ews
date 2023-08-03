<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxHoldResultType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the result of the mailbox hold request request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxHoldResultType extends Type
{
    /**
     * Contains the mailbox hold identifier.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $HoldId;

    /**
     * Specifies a list of one or more mailbox hold statuses.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfMailboxHoldStatusType
     */
    public $MailboxHoldStatuses;

    /**
     * Contains the search query for the hold.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Query;
}
