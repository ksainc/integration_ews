<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UploadItemType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single item to upload into a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UploadItemType extends Type
{
    /**
     * Specifies the action for uploading an item into a mailbox.
     *
     * This attribute is required.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CreateActionType
     */
    public $CreateAction;

    /**
     * Contains the data of a single item to upload into a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $Data;

    /**
     * Specifies whether the uploaded item is a folder associated item.
     *
     * A value of true indicates that the item is a folder associated item.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $IsAssociated;

    /**
     * Contains the unique identifier and change key of an item to create or
     * update in the Exchange store.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Represents the identifier of the parent folder where a new item is
     * created or that contains the item to update.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $ParentFolderId;
}
