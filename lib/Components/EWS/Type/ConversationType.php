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

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single conversation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConversationType extends Type
{
    /**
     * Contains a collection of strings that identify the categories that are
     * applied to all conversation items in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $Categories;

    /**
     * Represents the identifier of a conversation.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ConversationId;

    /**
     * Represents the conversation topic.
     *
     * This element is read-only.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $ConversationTopic;

    /**
     * Contains the aggregated flag status for conversation items in the current
     * folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\FlagStatusType
     */
    public $FlagStatus;

    /**
     * Contains the category list for all conversation items in a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $GlobalCategories;

    /**
     * Contains the aggregated flag status for all conversation items in a
     * mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\FlagStatusType
     */
    public $GlobalFlagStatus;

    /**
     * Contains a value that indicates whether at least one conversation item in
     * a mailbox has an attachment.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $GlobalHasAttachments;

    /**
     * Contains the aggregated importance for all conversation items in a
     * mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ImportanceChoicesType
     */
    public $GlobalImportance;

    /**
     * Contains a list of item classes that represents all the item classes of
     * the conversation items in a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfItemClassType
     */
    public $GlobalItemClasses;

    /**
     * Contains the collection of item identifiers for all conversation items in
     * a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $GlobalItemIds;

    /**
     * Contains the delivery time of the message that was last received in this
     * conversation across all folders in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $GlobalLastDeliveryTime;

    /**
     * Contains the total number of conversation items in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $GlobalMessageCount;

    /**
     * Contains the size of the conversation calculated from the size of all
     * conversation items in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $GlobalSize;

    /**
     * Contains the recipient list of a conversation aggregated across a
     * mailbox.
     *
     * This element is read-only.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $GlobalUniqueRecipients;

    /**
     * Contains a list of all the senders of conversation items in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $GlobalUniqueSenders;

    /**
     * Contains a list of all the people who have sent messages that are
     * currently unread in this conversation across all folders in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $GlobalUniqueUnreadSenders;

    /**
     * Contains a count of all the unread conversation items in the mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $GlobalUnreadCount;

    /**
     * Contains a value that indicates whether at least one conversation item in
     * the current folder has an attachment.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $HasAttachments;

    /**
     * Contains the aggregated importance for all conversation items in the
     * current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ImportanceChoicesType
     */
    public $Importance;

    /**
     * Contains a list of item classes that represents all the item classes of
     * the conversation items in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfItemClassType
     */
    public $ItemClasses;

    /**
     * Contains the collection of item identifiers for all conversation items in
     * the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ItemIds;

    /**
     * Contains the delivery time of the message that was last received in this
     * conversation in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastDeliveryTime;

    /**
     * Contains the total number of conversation items in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $MessageCount;

    /**
     * Contains the conversation size calculated from the size of all
     * conversation items in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $Size;

    /**
     * Contains the recipient list of a conversation aggregated from a
     * particular folder.
     *
     * This element is read-only.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $UniqueRecipients;

    /**
     * Contains a list of all the senders of conversation items in the current
     * folder.
     *
     * This element is read-only.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $UniqueSenders;

    /**
     * Contains a list of all the people who have sent messages that are
     * currently unread in this conversation in the current folder.
     *
     * This element is read-only.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $UniqueUnreadSenders;

    /**
     * Contains the count of unread conversation items within a folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var integer
     */
    public $UnreadCount;
}
