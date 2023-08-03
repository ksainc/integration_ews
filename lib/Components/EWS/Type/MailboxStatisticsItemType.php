<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxStatisticsItemType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines statistics for a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxStatisticsItemType extends Type
{
    /**
     * Display name of the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Specifies the total number of items in a search result.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $ItemCount;

    /**
     * Identifier for the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $MailboxId;

    /**
     * Total size of one or more mailbox items.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $Size;
}
