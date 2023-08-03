<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderItemsChangesType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a sequence array of change types that represent the types of
 * differences between the items on the client and the items on the Exchange
 * server.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderItemsChangesType extends Type
{
    /**
     * Identifies a single item to create in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderItemsCreateOrUpdateType[]
     */
    public $Create = array();

    /**
     * Identifies a single item to delete in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderItemsDeleteType[]
     */
    public $Delete = array();

    /**
     * Returned in SyncFolderItems operation responses when an item has been
     * read.
     *
     * This property is read-only.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderItemsReadFlagType[]
     */
    public $ReadFlagChange = array();

    /**
     * Identifies a single item to update in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderItemsCreateOrUpdateType[]
     */
    public $Update = array();
}
