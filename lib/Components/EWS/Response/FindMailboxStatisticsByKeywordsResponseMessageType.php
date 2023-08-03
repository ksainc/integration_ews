<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindMailboxStatisticsByKeywordsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response message for a FindMailboxStatisticsByKeywords
 * request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FindMailboxStatisticsByKeywordsResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the result of a mailbox search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\MailboxStatisticsSearchResultType
     */
    public $MailboxStatisticsSearchResult;
}
