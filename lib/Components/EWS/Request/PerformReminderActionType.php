<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\PerformReminderActionType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to perform a reminder action.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class PerformReminderActionType extends BaseRequestType
{
    /**
     * Specifies the actions for reminder items.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfReminderItemActionType
     */
    public $ReminderItemActions;
}
