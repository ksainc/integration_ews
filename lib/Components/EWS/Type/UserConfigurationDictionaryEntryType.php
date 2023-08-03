<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the contents of a single dictionary entry property.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationDictionaryEntryType extends Type
{
    /**
     * Specifies the dictionary key for a dictionary property.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryObjectType
     */
    public $DictionaryKey;

    /**
     * Specifies the dictionary value for a dictionary property.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryObjectType
     */
    public $DictionaryValue;
}
