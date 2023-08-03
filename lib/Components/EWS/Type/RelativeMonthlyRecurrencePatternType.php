<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RelativeMonthlyRecurrencePatternType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes a relative monthly recurrence pattern.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RelativeMonthlyRecurrencePatternType extends IntervalRecurrencePatternBaseType
{
    /**
     * Describes which week is used in a relative monthly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekIndexType
     */
    public $DayOfWeekIndex;

    /**
     * Describes which days of the week are in the relative monthly recurrence
     * pattern.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekType
     */
    public $DaysOfWeek;
}
