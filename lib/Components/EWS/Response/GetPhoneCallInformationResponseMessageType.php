<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetPhoneCallInformationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a single GetPhoneCallInformation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetPhoneCallInformationResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the state information for a phone call.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\PhoneCallInformationType
     */
    public $PhoneCallInformation;
}
