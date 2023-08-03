<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FreeBusyViewOptionsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the type of free/busy information returned in a response.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FreeBusyViewOptionsType extends Type
{
    /**
     * Represents the time difference between two successive slots in the
     * FreeBusyMerged view.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MergedFreeBusyIntervalInMinutes;

    /**
     * Defines the type of calendar information that a client requests.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\FreeBusyViewType
     */
    public $RequestedView;

    /**
     * Identifies the time span queried for the user availability information.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\Duration
     */
    public $TimeWindow;
}
