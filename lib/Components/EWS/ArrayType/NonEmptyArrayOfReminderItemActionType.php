<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfReminderItemActionType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines the actions for reminder items.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfReminderItemActionType extends ArrayType
{
    /**
     * Specifies the action for a reminder item.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ReminderItemActionType[]
     */
    public $ReminderItemAction = array();
}
