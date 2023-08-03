<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MessageType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a Microsoft Exchange e-mail message.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MessageType extends ItemType
{
    /**
     * Represents a collection of recipients to receive a blind carbon copy
     * (Bcc) of an e-mail.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $BccRecipients;

    /**
     * Represents a collection of recipients that will receive a copy of the
     * message.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $CcRecipients;

    /**
     * Contains a binary ID that represents the thread to which this message
     * belongs.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a base 64 class?
     */
    public $ConversationIndex;

    /**
     * Represents the conversation identifier.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ConversationTopic;

    /**
     * Represents the addressee from whom the message was sent.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SingleRecipientType
     */
    public $From;

    /**
     * Represents the Internet message identifier of an item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $InternetMessageId;

    /**
     * Indicates whether the sender of an item requests a delivery receipt.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsDeliveryReceiptRequested;

    /**
     * Indicates whether a message has been read.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsRead;

    /**
     * Indicates whether the sender of an item requests a read receipt.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsReadReceiptRequested;

    /**
     * Indicates whether a response to an e-mail message is requested.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsResponseRequested;

    /**
     * Represents the Usenet header that is used to correlate replies with their
     * original messages.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $References;

    /**
     * Identifies a set of addresses to which replies should be sent.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $ReplyTo;

    /**
     * Identifies the sender of an item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SingleRecipientType
     */
    public $Sender;

    /**
     * Contains a set of recipients of a message.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $ToRecipients;
}
