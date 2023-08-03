<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\RoutingType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the routing protocol for a recipient.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class RoutingType extends Enumeration
{
    /**
     * Route the email using the SMTP protocol.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SMTP = 'SMTP';
}
