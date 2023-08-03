<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DirectoryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a directory entry.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DirectoryEntryType extends Type
{
    /**
     * An identifier that contains an email address and display name that
     * represents a meeting room.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Id;
}
