<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPeriodsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of periods that define the time offset at different
 * stages of a time zone.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfPeriodsType extends ArrayType
{
    /**
     * The name, time offset, and unique identifier for a specific stage of a
     * time zone.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\PeriodType[]
     */
    public $Period = array();
}
