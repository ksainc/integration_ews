<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ItemChangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents an item identifier and the updates to apply to the item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ItemChangeType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $Id = null, object $Updates = null)
    {
        $this->ItemId = $Id;
        $this->Updates = $Updates;
    }

    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * This element is required if the OccurrenceItemId or RecurringMasterItemId
     * element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Identifies a single occurrence of a recurring item.
     *
     * This element is required if used. This element is required if the
     * RecurringMasterItemId or ItemId element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceItemIdType
     */
    public $OccurrenceItemId;

    /**
     * Identifies a recurrence master item by identifying one of its related
     * occurrence items' identifiers.
     *
     * This element is required if used. This element is required if the
     * OccurrenceItemId or ItemId element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringMasterItemIdType
     */
    public $RecurringMasterItemId;

    /**
     * Contains an array that defines append, set, and delete changes to item
     * properties.
     *
     * This element is required.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType
     */
    public $Updates;
}
