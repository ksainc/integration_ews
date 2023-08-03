<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PullSubscriptionRequestType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a subscription to a pull-based event notification subscription.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PullSubscriptionRequestType extends BaseSubscriptionRequestType
{
    /**
     * Represents the duration, in minutes, that the subscription can remain
     * idle without a GetEvents request from the client.
     *
     * @since Exchange 2007
     *
     * @var integer
     *
     * @todo Determine if we need SubscriptionTimeoutType.
     */
    public $Timeout;
}
