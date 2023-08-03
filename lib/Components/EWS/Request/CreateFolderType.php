<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\CreateFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to create a folder in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class CreateFolderType extends BaseRequestType
{
    /**
     * The element that contains all the folders to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFoldersType
     */
    public $Folders;

    /**
     * The element that identifies the location where the new folder is created.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ParentFolderId;
}
