<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UnsubscribeType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the properties used to unsubscribe from a subscription.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UnsubscribeType extends BaseRequestType
{
    /**
     * Represents the identifier for a subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SubscriptionId;
}
