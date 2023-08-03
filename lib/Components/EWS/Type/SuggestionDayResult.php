<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SuggestionDayResult.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single day that contains suggested meeting times.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SuggestionDayResult extends Type
{
    /**
     * Represents the date that contains the suggested meeting times.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $Date;

    /**
     * Represents the quality of the day for containing quality suggested
     * meeting times.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SuggestionQuality
     */
    public $DayQuality;

    /**
     * Contains an array of meeting suggestions.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSuggestion
     */
    public $SuggestionArray;
}
