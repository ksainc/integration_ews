<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\CalendarEvent.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a unique calendar item occurrence.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarEvent extends Type
{
    /**
     * Represents the start of a calendar event.
     *
     * This property is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartTime;

    /**
     * Represents the end of a calendar event.
     *
     * This property is required,
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndTime;

    /**
     * Represents the free/busy status set for a calendar event.
     *
     * This property is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\LegacyFreeBusyType
     */
    public $BusyType;

    /**
     * Provides additional information for a calendar event.
     *
     * This property is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarEventDetails
     */
    public $CalendarEventDetails;
}
