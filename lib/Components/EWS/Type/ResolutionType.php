<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ResolutionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single resolved entity.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ResolutionType extends Type
{
    /**
     * Represents an Exchange contact item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactItemType
     */
    public $Contact;

    /**
     * Identifies a mail-enabled Active Directory directory service object.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
