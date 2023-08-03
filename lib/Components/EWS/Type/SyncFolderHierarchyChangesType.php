<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderHierarchyChangesType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a sequenced array of change types that represent the type of
 * differences between the folders on the client and the folders on the computer
 * that is running Microsoft Exchange Server 2007.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderHierarchyChangesType extends Type
{
    /**
     * Identifies a single folder to create in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderHierarchyCreateOrUpdateType
     */
    public $Create;

    /**
     * Identifies a single folder to delete in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderHierarchyDeleteType
     */
    public $Delete;

    /**
     * Identifies a single folder to update in the local client store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderHierarchyCreateOrUpdateType
     */
    public $Update;
}
