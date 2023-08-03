<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PushSubscriptionRequestType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a subscription to a push-based event notification subscription.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PushSubscriptionRequestType extends BaseSubscriptionRequestType
{
    /**
     * Represents the frequency, specified in minutes, at which notification
     * messages will be sent to the client when no events have occurred.
     *
     * @since Exchange 2007
     *
     * @var integer
     *
     * @todo Determine if we need SubscriptionStatusFrequencyType.
     */
    public $StatusFrequency;

    /**
     * Represents the location of the client Web service for push notifications.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $URL;
}
