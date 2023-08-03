<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a set of dictionary property entries for a user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationDictionaryType extends Type
{
    /**
     * Specifies the contents of a single dictionary entry property.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryEntryType[]
     */
    public $DictionaryEntry = array();
}
