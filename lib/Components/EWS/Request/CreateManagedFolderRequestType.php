<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\CreateManagedFolderRequestType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add managed custom folders to a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class CreateManagedFolderRequestType extends BaseRequestType
{
    /**
     * Contains an array of named managed folders to add to a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFolderNamesType
     */
    public $FolderNames;

    /**
     * Identifies a mail-enabled Active Directory directory service object.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
