<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\FindFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to find folders in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class FindFolderType extends BaseRequestType
{
    /**
     * Identifies the folder properties to include in a FindFolder response.
     *
     * @since Exchange 2007
     *
     * @var OCA\EWS\Components\EWS\Type\FolderResponseShapeType
     */
    public $FolderShape;

    /**
     * Describes where the paged view starts and the maximum number of folders
     * returned in a FindFolder request.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var OCA\EWS\Components\EWS\Type\FractionalPageViewType
     */
    public $FractionalPageFolderView;

    /**
     * Describes how paged item information is returned in a FindFolder
     * response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var OCA\EWS\Components\EWS\Type\IndexedPageViewType
     */
    public $IndexedPageFolderView;

    /**
     * Identifies folders for the FindFolder operation to search.
     *
     * @since Exchange 2007
     *
     * @var OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $ParentFolderIds;

    /**
     * Defines a restriction or query that is used to filter folders in a
     * FindFolder operation.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var OCA\EWS\Components\EWS\Type\RestrictionType
     */
    public $Restriction;

    /**
     * Defines how a search is performed.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see OCA\EWS\Components\EWS\Enumeration\FolderQueryTraversalType
     */
    public $Traversal;
}
