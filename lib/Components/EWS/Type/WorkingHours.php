<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\WorkingHours.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the time zone settings and working hours for the requested mailbox
 * user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class WorkingHours extends Type
{
    /**
     * Contains elements that identify time zone information.
     *
     * This element also contains information about the transition between
     * standard time and daylight saving time.
     *
     * This element is required if the WorkingHours element is used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SerializableTimeZone
     */
    public $TimeZone;

    /**
     * Contains working period information for the mailbox user.
     *
     * This element is required if the WorkingHours element is used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfWorkingPeriod
     */
    public $WorkingPeriodArray;
}
