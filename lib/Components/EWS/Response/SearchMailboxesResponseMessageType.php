<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SearchMailboxesResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response message for a SearchMailboxes request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SearchMailboxesResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the result of the request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchMailboxesResultType
     */
    public $SearchMailboxesResult;
}
