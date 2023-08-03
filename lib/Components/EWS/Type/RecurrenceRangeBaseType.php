<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RecurrenceRangeBaseType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for recurrence ranges,
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class RecurrenceRangeBaseType extends Type
{
    /**
     * Represents the start date of a recurring task or calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a date object that extends DateTime.
     */
    public $StartDate;
}
