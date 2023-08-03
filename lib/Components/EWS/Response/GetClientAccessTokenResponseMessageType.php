<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetClientAccessTokenResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response message for a GetClientAccessToken request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetClientAccessTokenResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies a client access token.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ClientAccessTokenType
     */
    public $Token;
}
