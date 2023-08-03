<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetHoldOnMailboxesType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get the hold status for a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetHoldOnMailboxesType extends BaseRequestType
{
    /**
     * Contains the mailbox hold identifier.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $HoldId;
}
