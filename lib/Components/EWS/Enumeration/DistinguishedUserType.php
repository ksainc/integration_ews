<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\DistinguishedUserType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Identifies Anonymous and Default user accounts for delegate access.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DistinguishedUserType extends Enumeration
{
    /**
     * Describes the delegate access settings that anonymous users have on the
     * principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const ANONYMOUS = 'Anonymous';

    /**
     * Describes the default setting for delegate users who are added to the
     * principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const DEFAULT_USER = 'Default';
}
