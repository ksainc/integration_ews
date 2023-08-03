<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetUserConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to get a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetUserConfigurationType extends BaseRequestType
{
    /**
     * Represents the name of a user configuration object.
     *
     * This element must be present in a GetUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationNameType
     */
    public $UserConfigurationName;

    /**
     * Specifies the user configuration property types to return.
     *
     * This element must be present in a GetUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\UserConfigurationPropertyType
     */
    public $UserConfigurationProperties;
}
