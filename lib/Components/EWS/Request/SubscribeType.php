<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\SubscribeType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the properties used to create subscriptions.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SubscribeType extends BaseRequestType
{
    /**
     * Represents a subscription to a pull-based event notification.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PullSubscriptionRequestType
     */
    public $PullSubscriptionRequest;

    /**
     * Represents a subscription to a push-based event notification.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PushSubscriptionRequestType
     */
    public $PushSubscriptionRequest;

    /**
     * Represents a subscription to a streaming event notification.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\StreamingSubscriptionRequest
     */
    public $StreamingSubscriptionRequest;
}
