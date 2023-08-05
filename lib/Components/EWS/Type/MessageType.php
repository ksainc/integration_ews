<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
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
