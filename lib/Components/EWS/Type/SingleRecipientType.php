<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SingleRecipientType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies the delegate in a delegate access scenario.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SingleRecipientType extends Type
{
    /**
     * Identifies a mail-enabled Active Directory directory service object.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
