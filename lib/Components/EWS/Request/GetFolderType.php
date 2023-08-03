<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get a folder from a mailbox in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetFolderType extends BaseRequestType
{
    /**
     * Contains an array of folder identifiers that are used to identify folders
     * to get from a mailbox in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;

    /**
     * Identifies the properties to get for each folder identified in the
     * FolderIds element.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderResponseShapeType
     */
    public $FolderShape;
}
