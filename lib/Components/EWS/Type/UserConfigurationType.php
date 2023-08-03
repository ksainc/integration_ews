<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a single user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationType extends Type
{
    /**
     * Contains binary data property content for a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $BinaryData;

    /**
     * Defines a set of dictionary property entries for a user configuration
     * object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryType
     */
    public $Dictionary;

    /**
     * Defines the user configuration object item identifier.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Represents the name of a user configuration object.
     *
     * This element must be used when you create a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationNameType
     */
    public $UserConfigurationName;

    /**
     * Contains XML data property content for a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $XmlData;
}
