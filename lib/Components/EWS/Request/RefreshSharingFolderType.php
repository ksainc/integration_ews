<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RefreshSharingFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to refresh the specified local folder.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RefreshSharingFolderType extends BaseRequestType
{
    /**
     * Represents the identifier of the local folder in a sharing relationship.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $SharingFolderId;
}
