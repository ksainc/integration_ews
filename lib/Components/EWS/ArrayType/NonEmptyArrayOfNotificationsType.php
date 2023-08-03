<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of information about the subscription and the events that
 * have occurred since the last notification.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfNotificationsType extends ArrayType
{
    /**
     * Contains information about the subscription and the events that have
     * occurred since the last notification.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\NotificationType[]
     */
    public $Notification = array();
}
