<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetUserAvailabilityResponseType.
 */

namespace OCA\EWS\Components\EWS\Response;

use OCA\EWS\Components\EWS\Response;

/**
 * Defines the properties that define user availability information or suggested
 * meeting time information.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetUserAvailabilityResponseType extends Response
{
    /**
     * FContains the requested users' availability information and the response
     * status.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFreeBusyResponse
     */
    public $FreeBusyResponseArray;

    /**
     * Contains response status information and suggestion data for requested
     * meeting suggestions.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SuggestionsResponseType
     */
    public $SuggestionsResponse;
}
