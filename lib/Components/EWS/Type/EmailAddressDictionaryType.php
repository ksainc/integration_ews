<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\EmailAddressDictionaryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a collection of e-mail addresses for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class EmailAddressDictionaryType extends Type
{
    /**
     * Represents a single e-mail address for a contact.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressDictionaryEntryType[]
     */
    public $Entry;
}
