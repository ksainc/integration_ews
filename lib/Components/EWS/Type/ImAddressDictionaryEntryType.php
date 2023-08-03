<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ImAddressDictionaryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents an instant messaging (IM) address for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common EntryType class.
 * @todo Create a common TextEntryType class.
 */
class ImAddressDictionaryEntryType extends Type
{
    /**
     * Represents the IM address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $_;

    /**
     * Identifies the IM address.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ImAddressKeyType
     */
    public $Key;
}
