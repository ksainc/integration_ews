<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\StreamingSubscriptionRequest.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a subscription to a streaming event notification subscription.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class StreamingSubscriptionRequest extends Type
{
    /**
     * Contains a collection of event notifications that are used to create a
     * subscription.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationEventTypesType
     */
    public $EventTypes;

    /**
     * Contains an array of folder identifiers that are used to identify folders
     * to monitor for event notifications.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;

    /**
     * Indicates whether the server will subscribe to all folders in the user's
     * mailbox.
     *
     * A value of true indicates that the server will subscribe.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $SubscribeToAllFolders;
}
