<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\UserMailboxType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a user mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserMailboxType extends Type
{
    /**
     * The text value of the Id attribute is the identifier of the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;

    /**
     * Whether the mailbox is an archive mailbox.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsArchive;
}
