<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfOccurrenceInfoType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of recurring calendar item occurrences that have been
 * modified so that they are different than the recurrence master item.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfOccurrenceInfoType extends ArrayType
{
    /**
     * Represents a single modified occurrence of a recurring calendar item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceInfoType[]
     */
    public $Occurrence = array();
}
