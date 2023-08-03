<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a collection of physical addresses that are associated with a
 * contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PhysicalAddressDictionaryType extends Type
{
    /**
     * Describes a single physical address for a contact item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType[]
     */
    public $Entry;
}
