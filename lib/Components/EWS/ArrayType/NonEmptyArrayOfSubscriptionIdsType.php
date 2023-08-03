<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfSubscriptionIdsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of invalid subscription IDs.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfSubscriptionIdsType extends ArrayType
{
    /**
     * Represents the identifier for a subscription.
     *
     * @since Exchange 2010 SP1
     *
     * @var string[]
     */
    public $SubscriptionId = array();
}
