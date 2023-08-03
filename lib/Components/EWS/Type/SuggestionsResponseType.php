<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SuggestionsResponseType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents response status information and suggestion data for requested
 * meeting suggestions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SuggestionsResponseType extends Type
{
    /**
     * Provides descriptive information about the response status.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType
     */
    public $ResponseMessage;

    /**
     * Contains an array of meeting suggestions organized by date.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSuggestionDayResult
     */
    public $SuggestionDayResultArray;
}
