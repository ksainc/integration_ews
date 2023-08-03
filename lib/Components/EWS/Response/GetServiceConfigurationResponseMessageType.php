<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetServiceConfigurationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a GetServiceConfiguration request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetServiceConfigurationResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of service configuration response messages.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfServiceConfigurationResponseMessageType
     */
    public $ResponseMessages;
}
