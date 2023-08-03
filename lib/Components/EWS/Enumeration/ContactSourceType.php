<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ContactSourceType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Describes whether the contact is located in the Exchange store or Active
 * Directory Domain Services (AD DS).
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ContactSourceType extends Enumeration
{
    /**
     * Indicates that the contact is stored in Active Directory.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ACTIVE_DIRECTORY = 'ActiveDirectory';

    /**
     * Indicates that the contact is stored in the Exchange Store.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EXCHANGE_STORE = 'Store';
}
