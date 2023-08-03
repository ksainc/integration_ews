<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetSearchableMailboxesResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Contains the response to a GetSearchableMailboxes request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetSearchableMailboxesResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies an array of mailboxes that failed on search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFailedSearchMailboxesType
     */
    public $FailedMailboxes;

    /**
     * Contains an array of mailboxes.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSearchableMailboxesType
     */
    public $SearchableMailboxes;
}
