<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ConnectionStatusType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Provides a text description of the status of a streaming subscription.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConnectionStatusType extends Enumeration
{
    /**
     * Specifies that the connection has been closed.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CLOSED = 'Closed';

    /**
     * Specifies that the connection is open.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const OK = 'OK';
}
