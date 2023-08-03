<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderHierarchyCreateOrUpdateType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a single folder to create in the local client store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderHierarchyCreateOrUpdateType extends Type
{
    /**
     * Represents a folder that primarily contains calendar items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarFolderType
     */
    public $CalendarFolder;

    /**
     * Represents a contact folder in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactsFolderType
     */
    public $ContactsFolder;

    /**
     * Defines the folder to create, get, find, synchronize, or update.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderType
     */
    public $Folder;

    /**
     * Represents a search folder contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchFolderType
     */
    public $SearchFolder;

    /**
     * Represents a task folder contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TasksFolderType
     */
    public $TasksFolder;
}
