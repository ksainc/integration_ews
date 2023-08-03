<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\DeleteUserConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to delete a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DeleteUserConfigurationType extends BaseRequestType
{
    /**
     * Represents the name of the user configuration object to delete.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationNameType
     */
    public $UserConfigurationName;
}
