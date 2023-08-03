<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\OpenAsAdminOrSystemServiceType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * For internal use only. Not used by clients.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class OpenAsAdminOrSystemServiceType extends Type
{
    /**
     * Not intended for client use.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $ConnectingSID;

    /**
     * Not intended for client use.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $LogonType;
}
