<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetStreamingEventsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents the operation that is used by clients to request streaming
 * notifications from the server.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetStreamingEventsType extends BaseRequestType
{
    /**
     * Represents the number of minutes to keep a connection open.
     *
     * The value must be between 1 and 30, inclusive.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $ConnectionTimeout;

    /**
     * Represents the identifier for a subscription that is queried for events.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfSubscriptionIdsType
     */
    public $SubscriptionId;
}
