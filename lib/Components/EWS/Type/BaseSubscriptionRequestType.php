<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BaseSubscriptionRequestType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for subscription requests.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class BaseSubscriptionRequestType extends Type
{
    /**
     * Contains a collection of event notifications that are used to create a
     * subscription.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationEventTypesType
     */
    public $EventTypes;

    /**
     * Contains an array of folder identifiers that are used to identify folders
     * to monitor for event notifications.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;

    /**
     * Indicates whether to subscribe to all available folders.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $SubscribeToAllFolders;

    /**
     * Represents an event bookmark in the mailbox events table.
     *
     * This is used to create a subscription starting at an event represented by
     * the watermark.
     *
     * If the watermark from a Subscribe request is not found, an error response
     * will be returned to the client. This may occur if the watermark is older
     * than 30 days or if the watermark was never present in the mailbox.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Determine if we need a WatermarkType.
     */
    public $Watermark;
}
