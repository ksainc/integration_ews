<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderHierarchyDeleteType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a single folder to delete in the local client store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderHierarchyDeleteType extends Type
{
    /**
     * Contains the identifier and change key of a folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $FolderId;
}
