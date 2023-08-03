<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ItemAttachmentType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an Exchange item that is attached to another Exchange item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ItemAttachmentType extends AttachmentType
{
    /**
     * Represents an Exchange calendar item attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarItemType
     */
    public $CalendarItem;

    /**
     * Represents an Exchange contact item attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactItemType
     */
    public $Contact;

    /**
     * Represents a generic Exchange item attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemType
     */
    public $Item;

    /**
     * Represents a meeting cancellation in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingCancellationMessageType
     */
    public $MeetingCancellation;

    /**
     * Represents a meeting in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingMessageType
     */
    public $MeetingMessage;

    /**
     * Represents a meeting request in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingRequestMessageType
     */
    public $MeetingRequest;

    /**
     * Represents a meeting response in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingResponseMessageType
     */
    public $MeetingResponse;

    /**
     * Represents an Exchange e-mail message attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MessageType
     */
    public $Message;

    /**
     * Represents a post item in the Exchange store.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\PostItemType
     */
    public $PostItem;

    /**
     * Represents an Exchange task attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TaskType
     */
    public $Task;
}
