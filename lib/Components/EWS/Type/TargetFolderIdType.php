<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TargetFolderIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Indicates the folder that is targeted for actions that use folders.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TargetFolderIdType extends Type
{
    /**
     * Specifies the identifier of an address list.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\AddressListIdType
     */
    public $AddressListId;

    /**
     * Identifies folders that can be referenced by name.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType
     */
    public $DistinguishedFolderId;

    /**
     * Contains the identifier and change key of the context folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $FolderId;
}
