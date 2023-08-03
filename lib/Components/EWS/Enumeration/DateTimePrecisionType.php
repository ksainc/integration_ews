<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\DateTimePrecisionType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies the precision for returned date/time values.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DateTimePrecisionType extends Enumeration
{
    /**
     * Indicates that date/time values should be precise to the millisecond.
     *
     * @since Exchange 2010 SP2
     *
     * @var string
     */
    const MILLISECONDS = 'Milliseconds';

    /**
     * Indicates that date/time values should be precise to the second.
     *
     * @since Exchange 2010 SP2
     *
     * @var string
     */
    const SECONDS = 'Seconds';
}
