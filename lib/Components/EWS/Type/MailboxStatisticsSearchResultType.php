<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxStatisticsSearchResultType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the results of a keyword search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxStatisticsSearchResultType extends Type
{
    /**
     * Contains a single keyword search result.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\KeywordStatisticsSearchResultType
     */
    public $KeywordStatisticsSearchResult;

    /**
     * Identifies the user's mailbox.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\UserMailboxType
     */
    public $UserMailbox;
}
