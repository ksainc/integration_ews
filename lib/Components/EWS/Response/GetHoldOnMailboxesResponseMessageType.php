<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetHoldOnMailboxesResponse.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to get the hold status for a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetHoldOnMailboxesResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the result of the GetHoldOnMailboxes request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\MailboxHoldResultType
     */
    public $MailboxHoldResult;
}
