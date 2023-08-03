<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\BaseMoveCopyFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Base class for folder move and copy requests.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class BaseMoveCopyFolderType extends BaseRequestType
{
    /**
     * Represents the destination folder for a copied folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;

    /**
     * Represents the destination folder for a copied folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ToFolderId;
}
