<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ClientAccessTokenRequestType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a single token request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ClientAccessTokenRequestType extends Type
{
    /**
     * Specifies the identifier of an app.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;

    /**
     * Specifies a token scope.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Scope;

    /**
     * Identifies the type of client access token.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ClientAccessTokenTypeType
     */
    public $TokenType;
}
