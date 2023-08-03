<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\EmailAddressKeyType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the key for an email address.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class EmailAddressKeyType extends Enumeration
{
    /**
     * Key for a contacts first email address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EMAIL_ADDRESS_1 = 'EmailAddress1';

    /**
     * Key for a contacts second email address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EMAIL_ADDRESS_2 = 'EmailAddress2';

    /**
     * Key for a contacts third email address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EMAIL_ADDRESS_3 = 'EmailAddress3';
}
