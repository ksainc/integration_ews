<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes the start date and the number of occurrences of a recurring item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class NumberedRecurrenceRangeType extends RecurrenceRangeBaseType
{
    /**
     * Contains the number of occurrences of a recurring item.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumberOfOccurrences;
}
