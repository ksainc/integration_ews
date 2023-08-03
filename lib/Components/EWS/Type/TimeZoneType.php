<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TimeZoneType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a time zone.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TimeZoneType extends Type
{
    /**
     * Represents the hourly offset from UTC for the current time zone.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $BaseOffset;

    /**
     * Represents the date and time when the time changes from standard time to
     * daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeChangeType
     */
    public $Daylight;

    /**
     * Represents the date and time when the time changes from daylight saving
     * time to standard time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeChangeType
     */
    public $Standard;

    /**
     * The name of the time zone.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $TimeZoneName;
}
