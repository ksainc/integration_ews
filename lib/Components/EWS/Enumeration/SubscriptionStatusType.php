<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SubscriptionStatusType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Describes the status of a push subscription.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SubscriptionStatusType extends Enumeration
{
    /**
     * Indicates that the notification has been received and the subscription is
     * to continue.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OK = 'OK';

    /**
     * Indicates that push notifications from the subscription should be ceased.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const UNSUBSCRIBE = 'Unsubscribe';
}
