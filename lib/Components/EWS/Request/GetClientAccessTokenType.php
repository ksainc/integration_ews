<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetClientAccessTokenType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get a client access token.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetClientAccessTokenType extends BaseRequestType
{
    /**
     * Contains an array of token requests.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfClientAccessTokenRequestsType
     */
    public $TokenRequests;
}
