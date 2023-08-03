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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Identifies items of any type for numerous requests.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfAllItemsType extends ArrayType
{
    /**
     * Represents an Accept reply to a meeting request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AcceptItemType[]
     */
    public $AcceptItem = array();

    /**
     * Used to accept an invitation that allows access to another user’s
     * calendar or contacts data.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\AcceptSharingInvitationType[]
     */
    public $AcceptSharingInvitation = array();

    /**
     * Represents an Exchange calendar item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarItemType[]
     */
    public $CalendarItem = array();

    /**
     * Represents the response object that is used to cancel a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CancelCalendarItemType[]
     */
    public $CancelCalendarItem = array();

    /**
     * Represents an Exchange contact item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactItemType[]
     */
    public $Contact = array();

    /**
     * Represents a Decline reply to a meeting request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeclineItemType[]
     */
    public $DeclineItem = array();

    /**
     * Represents a distribution list.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DistributionListType[]
     */
    public $DistributionList = array();

    /**
     * Contains an Exchange store item to forward to recipients.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ForwardItemType[]
     */
    public $ForwardItem = array();

    /**
     * Represents an item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemType[]
     */
    public $Item = array();

    /**
     * Represents a meeting cancellation in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingCancellationMessageType[]
     */
    public $MeetingCancellation = array();

    /**
     * Represents a meeting message in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingMessageType[]
     */
    public $MeetingMessage = array();

    /**
     * Represents a meeting request in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingRequestMessageType[]
     */
    public $MeetingRequest = array();

    /**
     * Represents a meeting response in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MeetingResponseMessageType[]
     */
    public $MeetingResponse = array();

    /**
     * Represents an Exchange e-mail message.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MessageType[]
     */
    public $Message = array();

    /**
     * Contains a post item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PostItemType[]
     */
    public $PostItem = array();

    /**
     * Contains a reply to a post item.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\PostReplyItemType[]
     */
    public $PostReplyItem = array();

    /**
     * Represents a response object that is used to remove a meeting item when a
     * MeetingCancellation message is received.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RemoveItemType[]
     */
    public $RemoveItem = array();

    /**
     * Contains a reply to the sender and all identified recipients of an item
     * in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyAllToItemType[]
     */
    public $ReplyAllToItem = array();

    /**
     * Contains a reply to the sender of an item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyToItemType[]
     */
    public $ReplyToItem = array();

    /**
     * Used to suppress read receipts.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SuppressReadReceiptType[]
     */
    public $SuppressReadReceipt = array();

    /**
     * Represents a task in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TaskType[]
     */
    public $Task = array();

    /**
     * Represents a Tentative reply to a meeting request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TentativelyAcceptItemType[]
     */
    public $TentativelyAcceptItem = array();
}
