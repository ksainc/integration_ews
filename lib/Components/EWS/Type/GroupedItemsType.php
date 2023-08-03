<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\GroupedItemsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a collection of items that are the result of a grouped FindItem
 * operation call.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class GroupedItemsType extends Type
{
    /**
     * Represents the property value that is used to group items in a grouped
     * FindItem operation call.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $GroupIndex;

    /**
     * Contains an array of grouped items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRealItemsType
     */
    public $Items;
}
