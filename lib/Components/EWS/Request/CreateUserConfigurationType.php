<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\CreateUserConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to create a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class CreateUserConfigurationType extends BaseRequestType
{
    /**
     * Represents a single user configuration object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationType
     */
    public $UserConfiguration;
}
