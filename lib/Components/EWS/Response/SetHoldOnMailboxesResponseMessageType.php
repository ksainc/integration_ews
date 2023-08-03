<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SetHoldOnMailboxesResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a SetHoldOnMailboxes request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SetHoldOnMailboxesResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the result of the request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\MailboxHoldResultType
     */
    public $MailboxHoldResult;
}
