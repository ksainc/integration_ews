<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetSharingFolderResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single GetSharingFolder operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetSharingFolderResponseMessageType extends ResponseMessageType
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
