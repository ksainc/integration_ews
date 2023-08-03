<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UpdateFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents the operation that is used to update properties for a specified
 * folder.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UpdateFolderType extends BaseRequestType
{
    /**
     * Contains a collection of changes for a specified folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFolderChangesType
     */
    public $FolderChanges;
}
