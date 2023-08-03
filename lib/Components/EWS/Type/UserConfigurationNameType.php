<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserConfigurationNameType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents the name of a user configuration object. The user configuration
 * object name is the identifier for a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationNameType extends TargetFolderIdType
{
    /**
     * The name of a user configuration object.
     *
     * This attribute is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;
}
