<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ExchangeImpersonationType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the account to impersonate within a request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExchangeImpersonationType extends Type
{
    /**
     * Represents an account to impersonate when you are using the
     * ExchangeImpersonation SOAP header.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ConnectingSIDType
     */
    public $ConnectingSID;
}
