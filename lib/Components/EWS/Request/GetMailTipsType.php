<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetMailTipsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents the recipients and types of mail tips to retrieve.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetMailTipsType extends BaseRequestType
{
    /**
     * Contains the types of mail tips requested from the service.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailTipTypes
     */
    public $MailTipsRequested;

    /**
     * Contains a list of recipients to check for mail tips.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $Recipients;

    /**
     * Contains an e-mail address that a user is trying to send as.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $SendingAs;
}
