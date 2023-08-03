<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UpdateUserConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to update a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UpdateUserConfigurationType extends BaseRequestType
{
    /**
     * Defines a single user configuration object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationType
     */
    public $UserConfiguration;
}
