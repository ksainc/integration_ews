<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\WeeklyRecurrencePatternType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes a weekly recurrence pattern.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class WeeklyRecurrencePatternType extends IntervalRecurrencePatternBaseType
{
    /**
     * Describes which days of the week are in the weekly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DaysOfWeekType
     */
    public $DaysOfWeek;

    /**
     * Specifies the first day of the week.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekType
     */
    public $FirstDayOfWeek;
}
