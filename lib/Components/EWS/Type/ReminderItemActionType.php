<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ReminderItemActionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the action for a reminder item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ReminderItemActionType extends Type
{
    /**
     * Specifies the action to take on the reminder.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ReminderActionType
     */
    public $ActionType;

    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Specifies a new time for a reminder.
     *
     * This is used when the ActionType element is set to Snooze, in order to
     * delay the reminder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $NewReminderTime;
}
