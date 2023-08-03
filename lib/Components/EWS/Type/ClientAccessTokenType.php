<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ClientAccessTokenType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a client access token.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ClientAccessTokenType extends Type
{
    /**
     * Specifies the identifier of the token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;

    /**
     * Specifies the type of token.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ClientAccessTokenTypeType
     */
    public $TokenType;

    /**
     * Specifies the encoded client access token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $TokenValue;

    /**
     * Specifies the time, in minutes, that the token is valid.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $TTL;
}
