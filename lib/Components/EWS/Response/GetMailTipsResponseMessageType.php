<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetMailTipsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response message for a GetMailTips Operation.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetMailTipsResponseMessageType extends ResponseMessageType
{
    /**
     * Represents a list of mail tips response messages.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfMailTipsResponseMessageType
     */
    public $ResponseMessages;
}
