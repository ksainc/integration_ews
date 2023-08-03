<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FailedSearchMailboxType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies the error message for a mailbox that failed on search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FailedSearchMailboxType extends Type
{
    /**
     * Specifies the error code of the mailbox that failed the search.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $ErrorCode;

    /**
     * Represents the reason for the validation error.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorMessage;

    /**
     * Specifies whether the mailbox is an archive.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsArchive;

    /**
     * Contains an identifier for the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Mailbox;
}
