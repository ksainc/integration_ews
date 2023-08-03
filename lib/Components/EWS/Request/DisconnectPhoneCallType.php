<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\DisconnectPhoneCallType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to disconnect a call.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DisconnectPhoneCallType extends BaseRequestType
{
    /**
     * Specifies the identifier of the call to disconnect.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\PhoneCallIdType
     */
    public $PhoneCallId;
}
