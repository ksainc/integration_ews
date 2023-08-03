<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of ItemChange elements that identify items and the
 * updates to apply to the items.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfItemChangesType extends ArrayType
{
    /**
     * Contains an item identifier and the updates to apply to the item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemChangeType[]
     */
    public $ItemChange = array();
}
