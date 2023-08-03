<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFoldersType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of folders that are used in folder operations.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfFoldersType extends ArrayType
{
    /**
     * Represents a folder that primarily contains calendar items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarFolderType[]
     */
    public $CalendarFolder = array();

    /**
     * Represents a Contacts folder in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactsFolderType[]
     */
    public $ContactsFolder = array();

    /**
     * Identifies a folder to create, get, find, synchronize, or update.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderType[]
     */
    public $Folder = array();

    /**
     * Represents a Search folder contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchFolderType[]
     */
    public $SearchFolder = array();

    /**
     * Represents a Tasks folder in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TasksFolderType[]
     */
    public $TasksFolder = array();
}
