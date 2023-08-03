<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SendNotificationResultType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the response of a client application to a push notification.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SendNotificationResultType extends Type
{
    /**
     * Describes the status of a push subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SubscriptionStatusType
     */
    public $SubscriptionStatus;
}
