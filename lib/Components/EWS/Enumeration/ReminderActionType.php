<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ReminderActionType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the action to take on a reminder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ReminderActionType extends Enumeration
{
    /**
     * Dismiss the reminder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DISMISS = 'Dismiss';

    /**
     * Snooze the reminder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const SNOOZE = 'Snooze';
}
