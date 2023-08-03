<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RecurrenceType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the recurrence pattern for calendar items and meeting requests.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Implement RecurrencePatternTypes trait.
 * @todo Implement RecurrenceRangeTypes trait.
 */
class RecurrenceType extends Type
{
    /**
     * Represents a monthly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AbsoluteMonthlyRecurrencePatternType
     */
    public $AbsoluteMonthlyRecurrence;

    /**
     * Represents a yearly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AbsoluteYearlyRecurrencePatternType
     */
    public $AbsoluteYearlyRecurrence;

    /**
     * Describes the frequency, in days, in which a calendar item or task
     * recurs.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DailyRecurrencePatternType
     */
    public $DailyRecurrence;

    /**
     * Describes the start date and the end date of an item recurrence pattern.
     *
     * The use of this element excludes the use of the NoEndRecurrence and
     * NumberedRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType
     */
    public $EndDateRecurrence;

    /**
     * Describes a recurrence pattern that does not have a defined end date.
     *
     * The use of this element excludes the use of the EndDateRecurrence and
     * NumberedRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NoEndRecurrenceRangeType
     */
    public $NoEndRecurrence;

    /**
     * Describes the start date and the number of occurrences of a recurring
     * item.
     *
     * The use of this element excludes the use of the NoEndRecurrence and
     * EndDateRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType
     */
    public $NumberedRecurrence;

    /**
     * Describes a relative monthly recurrence pattern for a recurring calendar
     * item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RelativeMonthlyRecurrencePatternType
     */
    public $RelativeMonthlyRecurrence;

    /**
     * Describes a relative yearly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType
     */
    public $RelativeYearlyRecurrence;

    /**
     * Describes the frequency, in weeks, and the days that a calendar item or
     * task recurs.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WeeklyRecurrencePatternType
     */
    public $WeeklyRecurrence;
}
