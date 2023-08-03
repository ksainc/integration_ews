<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FolderType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines a folder to create, get, find, synchronize, or update.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FolderType extends BaseFolderType
{
    /**
     * Contains all the configured permissions for a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\PermissionSetType
     */
    public $PermissionSet;

    /**
     * Represents the count of unread items within a given folder.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $UnreadCount;
}
