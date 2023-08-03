<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BaseObjectChangedEventType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an event in which an item or folder is created.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BaseObjectChangedEventType extends BaseNotificationEventType
{
    /**
     * Represents the identifier of the folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $FolderId;

    /**
     * Represents the identifier of the item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Represents the identifier of the folder that contains the copy.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $ParentFolderId;

    /**
     * Represents the timestamp of a copy item/folder mailbox event.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $TimeStamp;
}
