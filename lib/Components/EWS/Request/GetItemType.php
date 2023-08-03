<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetItemType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get an item from a mailbox in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetItemType extends BaseRequestType
{
    /**
     * Contains the unique identities of items, occurrence items, and recurring
     * master items that are used to get items from the Exchange store.
     *
     * These items represent contacts, tasks, messages, calendar items, meeting
     * requests, and other valid items in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ItemIds;

    /**
     * Identifies the item properties and content to include in a GetItem
     * response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemResponseShapeType
     */
    public $ItemShape;
}
