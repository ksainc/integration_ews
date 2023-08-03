<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderItemsDeleteType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a single item to delete in the local client store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderItemsDeleteType extends Type
{
    /**
     * Contains the unique identifier and change key of an item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;
}
