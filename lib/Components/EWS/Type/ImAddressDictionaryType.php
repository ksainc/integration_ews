<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ImAddressDictionaryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a collection of instant messaging addresses for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ImAddressDictionaryType extends Type
{
    /**
     * Represents an instant messaging address for a contact.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ImAddressDictionaryEntryType
     */
    public $Entry;
}
