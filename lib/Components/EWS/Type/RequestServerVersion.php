<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RequestServerVersion.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the Microsoft Exchange Server version of a request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RequestServerVersion extends Type
{
    /**
     * Identifies the Exchange Server version used in the request.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ExchangeVersionType
     */
    public $Version;
}
