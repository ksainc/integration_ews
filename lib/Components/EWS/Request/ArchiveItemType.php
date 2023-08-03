<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\ArchiveItemType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the source folder Id and an array of item Ids for the associated
 * archive item.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ArchiveItemType extends BaseRequestType
{
    /**
     * Specifies the Id of the source folder for the archive item.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ArchiveSourceFolderId;

    /**
     * Contains the unique identities of items to archive.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ItemIds;
}
