<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a collection of telephone numbers for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PhoneNumberDictionaryType extends Type
{
    /**
     * Represents a telephone number for a contact.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType[]
     */
    public $Entry;
}
