<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderItemsReadFlagType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies whether or not an item has been read.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderItemsReadFlagType extends Type
{
    /**
     * Indicates whether the read-flag has been set to true.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsRead;

    /**
     * Identifies the item for which the read-flag has been changed.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;
}
