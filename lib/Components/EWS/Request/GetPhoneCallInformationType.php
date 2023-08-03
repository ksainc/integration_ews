<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetPhoneCallInformationType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to get telephone call information.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetPhoneCallInformationType extends BaseRequestType
{
    /**
     * Specifies the identifier of a phone call.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\PhoneCallIdType
     */
    public $PhoneCallId;
}
