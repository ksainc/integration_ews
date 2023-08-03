<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TaskRecurrenceType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines the recurrence pattern for recurring tasks.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Implement TaskRecurrencePatternTypes trait.
 * @todo Implement RecurrenceRangeTypes trait.
 */
class TaskRecurrenceType extends RecurrenceType
{
    /**
     * Describes how many days after the completion of the current task the next
     * occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DailyRegeneratingPatternType
     */
    public $DailyRegeneration;

    /**
     * Describes how many months after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MonthlyRegeneratingPatternType
     */
    public $MonthlyRegeneration;

    /**
     * Describes how many weeks after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WeeklyRegeneratingPatternType
     */
    public $WeeklyRegeneration;

    /**
     * Describes how many years after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\YearlyRegeneratingPatternType
     */
    public $YearlyRegeneration;
}
