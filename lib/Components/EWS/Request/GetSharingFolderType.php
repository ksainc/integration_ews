<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetSharingFolderType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get the local folder identifier of a specified shared
 * folder.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetSharingFolderType extends BaseRequestType
{
    /**
     * Describes the type of data that is shared by a shared folder.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SharingDataType
     */
    public $DataType;

    /**
     * Represents the identifier of the shared folder whose local folder
     * identifier should be returned.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SharedFolderId;

    /**
     * Represents the SMTP e-mail address of the other party in the sharing
     * relationship.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SmtpAddress;
}
