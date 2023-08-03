<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\MailTipsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents mail tips settings.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class MailTipsResponseMessageType extends ResponseMessageType
{
    /**
     * Represents values for various types of mail tips.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\MailTips
     */
    public $MailTips;
}
