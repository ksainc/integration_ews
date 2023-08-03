<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetRemindersType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get reminders.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetRemindersType extends BaseRequestType
{
    /**
     * Specifies the beginning of the time span to query for reminders.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $BeginTime;

    /**
     * Represents the end of the time span to query for reminders.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndTime;

    /**
     * Specifies the maximum number of items to return in the request.
     *
     * This number cannot be less than zero or greater than 200.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $MaxItems;

    /**
     * Specifies the type of reminders to return.
     *
     * Possible values are All, Current, or Old.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ReminderType;
}
