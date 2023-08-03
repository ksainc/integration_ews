<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RecurringMasterItemIdRanges.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines an occurrence range.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RecurringMasterItemIdRanges extends ItemIdType
{
    /**
     * Specifies an array of recurrence ranges.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfOccurrenceRangesType
     */
    public $Ranges;
}
