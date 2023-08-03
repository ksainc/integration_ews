<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationEventTypesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of event notification types that are used to create a
 * subscription.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfNotificationEventTypesType extends ArrayType
{
    /**
     * Represents a requested event notification type that is used to create a
     * subscription.
     *
     * @since Exchange 2007
     *
     * @var string[]
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\NotificationEventTypeType[]
     */
    public $EventType = array();
}
