<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetUserConfigurationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents a response that returns a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetUserConfigurationResponseMessageType extends ResponseMessageType
{
    /**
     * Contains a single user configuration object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationType
     */
    public $UserConfiguration;
}
