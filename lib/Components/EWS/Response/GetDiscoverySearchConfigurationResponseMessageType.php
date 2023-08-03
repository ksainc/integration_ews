<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetDiscoverySearchConfigurationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetDiscoverySearchConfiguration request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetDiscoverySearchConfigurationResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies an array of discovery search configurations.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfDiscoverySearchConfigurationType
     */
    public $DiscoverySearchConfigurations;
}
