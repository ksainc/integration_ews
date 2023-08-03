<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\EmailAddressType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifier for a fully resolved email address
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class EmailAddressType extends BaseEmailAddressType
{
    /*Constructor method with arguments*/
    public function __construct(string $Address = null, string $Name = null)
    {
        $this->EmailAddress = $Address;
        $this->Name = $Name;
    }

    /**
     * Represents the e-mail address of the mailbox user.
     *
     * @since Exchnage 2007
     *
     * @var string
     */
    public $Address;

    /**
     * The e-mail address that is represented.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $EmailAddress;

    /**
     * Specifies the item identifier for the e-mail address.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Specifies the type of mailbox.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailboxTypeType
     */
    public $MailboxType;

    /**
     * Specifies the name of the mailbox that is associated with the e-mail
     * address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Name;

    /**
     * Specifies the type of routing for the e-mail address.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RoutingType
     */
    public $RoutingType;
}
