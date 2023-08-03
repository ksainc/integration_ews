<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\DeleteFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to delete folders from a mailbox in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DeleteFolderType extends BaseRequestType
{
    /**
     * Describes how a folder is deleted. This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DisposalType
     */
    public $DeleteType;

    /**
     * Contains an array of folder identifiers that are used to identify folders
     * to delete.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;
}
