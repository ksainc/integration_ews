<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SetFolderFieldType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an update that sets the value for a single property on a folder in
 * an UpdateFolder operation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SetFolderFieldType extends FolderChangeDescriptionType
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
     * Represents a Contacts folder in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactsFolderType
     */
    public $ContactsFolder;

    /**
     * Identifies a folder to update.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderType
     */
    public $Folder;

    /**
     * Represents a search folder that is contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchFolderType
     */
    public $SearchFolder;

    /**
     * Represents a Tasks folder that is contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TasksFolderType
     */
    public $TasksFolder;
}
