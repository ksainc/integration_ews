<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetSharingMetadataResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetSharingMetadataResponseMessageType extends ResponseMessageType
{
    /**
     * Contains a collection of data structures that a client can use to
     * authorize the sharing of its calendar or contact data with other clients.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEncryptedSharedFolderDataType
     */
    public $EncryptedSharedFolderDataCollection;

    /**
     * Represents recipients of the folder sharing request that are invalid.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfInvalidRecipientsType
     */
    public $InvalidRecipients;
}
