<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetInboxRulesResponseType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a GetInboxRules operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetInboxRulesResponseType extends ResponseMessageType
{
    /**
     * Represents an array of the rules in the user's mailbox.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRulesType
     */
    public $InboxRules;

    /**
     * Indicates whether a Microsoft Outlook rule blob exists in the user's
     * mailbox.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $OutlookRuleBlobExists;
}
