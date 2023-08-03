<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FreeBusyView.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents availability information for a specific user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FreeBusyView extends Type
{
    /**
     * Contains a set of unique calendar item occurrences that represent the
     * requested user's availability.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfCalendarEvent
     */
    public $CalendarEventArray;

    /**
     * Represents the type of requested free/busy information returned in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\FreeBusyViewType
     */
    public $FreeBusyViewType;

    /**
     * Contains the merged free/busy stream of data.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $MergedFreeBusy;

    /**
     * Represents the time zone settings and working hours for the requested
     * mailbox user.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WorkingHours
     */
    public $WorkingHours;
}
