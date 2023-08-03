<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ReminderGroup.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines whether the reminder is for a calendar item or a task.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ReminderGroup extends Enumeration
{
    /**
     * Specifies that the reminder is for a calendar item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CALENDAR = 'Calendar';

    /**
     * Specifies that the reminder is for a task item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASK = 'Task';
}
