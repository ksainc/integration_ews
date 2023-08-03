<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\WorkingPeriod.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the work week days and hours of the mailbox user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class WorkingPeriod extends Type
{
    /**
     * Contains the list of working days scheduled for the mailbox user.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DaysOfWeekType
     */
    public $DayOfWeek;

    /**
     * Represents the end of the working day for a mailbox user.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $EndTimeInMinutes;

    /**
     * Represents the start of the working day for a mailbox user.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $StartTimeInMinutes;
}
