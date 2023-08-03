<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RecurringDateTransitionType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a time zone transition that occurs on a specific date each year.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RecurringDateTransitionType extends RecurringTimeTransitionType
{
    /**
     * The day of the month on which the time zone transition occurs.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $Day;
}
