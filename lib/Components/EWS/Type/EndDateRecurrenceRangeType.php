<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes the start date and the end date of an item recurrence pattern.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class EndDateRecurrenceRangeType extends RecurrenceRangeBaseType
{
    /**
     * Represents the end date of a recurring task or calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a date object that extends DateTime.
     */
    public $EndDate;
}
