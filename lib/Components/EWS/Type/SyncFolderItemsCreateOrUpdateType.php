<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SyncFolderItemsCreateOrUpdateType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a single item to create in the local client store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SyncFolderItemsCreateOrUpdateType extends Type
{
    /**
     * Represents an Exchange calendar item to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarItemType
     */
    public $CalendarItem;

    /**
     * Represents an Exchange contact item to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactItemType
     */
    public $Contact;

    /**
     * Represents a distribution list to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DistributionListType
     */
    public $DistributionList;

    /**
     * Represents a generic Exchange item to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemType
     */
    public $Item;

    /**
     * Represents a meeting cancellation to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingCancellationMessageType
     */
    public $MeetingCancellation;

    /**
     * Represents a meeting message to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingMessageType
     */
    public $MeetingMessage;

    /**
     * Represents a meeting request to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingRequestMessageType
     */
    public $MeetingRequest;

    /**
     * Represents a meeting response to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingResponseMessageType
     */
    public $MeetingResponse;

    /**
     * Represents an Exchange e-mail message to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MessageType
     */
    public $Message;

    /**
     * Represents an Exchange post to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PostItemType
     */
    public $PostItem;

    /**
     * Represents a task to create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TaskType
     */
    public $Task;
}
