<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\IntervalRecurrencePatternBaseType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Base class for recurrence patterns with an interval.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class IntervalRecurrencePatternBaseType extends RecurrencePatternBaseType
{
    /**
     * Defines the interval between two consecutive recurring pattern items.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Interval;
}
