<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\PlayOnPhoneResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a request to play a voice mail over the telephone.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class PlayOnPhoneResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the telephone call identifier.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\PhoneCallIdType
     */
    public $PhoneCallId;
}
