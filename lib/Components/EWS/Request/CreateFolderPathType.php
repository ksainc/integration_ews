<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\CreateFolderPathType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to create a folder path.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class CreateFolderPathType extends BaseRequestType
{
    /**
     * Identifies the folder in which a new folder is created.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ParentFolderId;

    /**
     * Contains an array of folders that indicate the relative folder path of
     * the folder path to be created.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFoldersType
     */
    public $RelativeFolderPath;
}
