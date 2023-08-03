<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes a relative yearly recurrence pattern.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RelativeYearlyRecurrencePatternType extends RecurrencePatternBaseType
{
    /**
     * Describes which week in a month is used in a relative yearly recurrence
     * pattern.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekIndexType
     */
    public $DayOfWeekIndex;

    /**
     * Describes the days of the week that are used in item recurrence patterns.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekType
     */
    public $DaysOfWeek;

    /**
     * Describes the month when a yearly recurring item occurs.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MonthNamesType
     */
    public $Month;
}
