<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetInboxRulesRequestType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get the Inbox rules on a mailbox in the server store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetInboxRulesRequestType extends BaseRequestType
{
    /**
     * Represents the SMTP address of the user whose Inbox rules are to be
     * retrieved.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $MailboxSmtpAddress;
}
