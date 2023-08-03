<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\EmailAddressDictionaryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single e-mail address for a contact.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common EntryType class.
 * @todo Create a common TextEntryType class.
 */
class EmailAddressDictionaryEntryType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Key = null, string $Address = null, string $Name = null)
    {
        $this->Key = $Key;
        $this->_ = $Address;
        $this->Name = $Name;
    }
    
    /**
     * The email address represented by this entry.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $_;

    /**
     * Identifies the e-mail address.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\EmailAddressKeyType
     */
    public $Key;

    /**
     * Defines the mailbox type of a mailbox user.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailboxTypeType
     */
    public $MailboxType;

    /**
     * Defines the name of the mailbox user.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;

    /**
     * Defines the routing that is used for the mailbox.
     *
     * This attribute is optional and defaults to SMTP.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RoutingType
     */
    public $RoutingType;
}
