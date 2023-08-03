<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\OccurrencesRangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a range of calendar item occurrences for a repeating calendar item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class OccurrencesRangeType extends Type
{
    /**
     * Start date of the recurring item range.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $Start;

    /**
     * End date of the recurring item range.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $End;

    /**
     * Number of occurrences of the recurring item.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $Count;

    /**
     * Whether or not the client should compare the original start time with the
     * new start time.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $CompareOriginalStartTime;
}
