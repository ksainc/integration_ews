<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a telephone number for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common EntryType class.
 * @todo Create a common TextEntryType class.
 */
class PhoneNumberDictionaryEntryType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Key = null, string $Number = null)
    {
        $this->Key = $Key;
        $this->_ = $Number;
    }

    /**
     * Value that represents the telephone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $_;

    /**
     * Identifies the telephone number.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PhoneNumberKeyType
     */
    public $Key;
}
