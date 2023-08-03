<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryObjectType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the value of a dictionary property.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationDictionaryObjectType extends Type
{
    /**
     * Specifies the dictionary object type.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\UserConfigurationDictionaryObjectTypesType
     */
    public $Type;

    /**
     * Specifies the dictionary object value as a string.
     *
     * @since Exchange 2010
     *
     * @var string[]
     */
    public $Value = array();
}
