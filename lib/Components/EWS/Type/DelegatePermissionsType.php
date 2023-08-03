<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DelegatePermissionsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Contains the delegate permission-level settings for a user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DelegatePermissionsType extends Type
{
    /**
     * Contains the permissions for the default Calendar folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $CalendarFolderPermissionLevel;

    /**
     * Contains the permissions for the default Contacts folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $ContactsFolderPermissionLevel;

    /**
     * Contains the permissions for the default Inbox folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $InboxFolderPermissionLevel;

    /**
     * Contains the permissions for the default Journal folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $JournalFolderPermissionLevel;

    /**
     * Contains the permissions for the default Notes folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $NotesFolderPermissionLevel;

    /**
     * Contains the permissions for the default Task folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DelegateFolderPermissionLevelType
     */
    public $TasksFolderPermissionLevel;
}
