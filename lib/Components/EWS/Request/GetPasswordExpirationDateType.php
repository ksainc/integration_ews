<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetPasswordExpirationDateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get the password expiration date for an email account.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetPasswordExpirationDateType extends BaseRequestType
{
    /**
     * Represents the email address of the email account for which the password
     * expiration date is to be returned.
     *
     * @since Exchange 2010 SP2
     *
     * @var string
     */
    public $MailboxSmtpAddress;
}
