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
 * Represents a collection of all the response objects that are associated with
 * an item in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfResponseObjectsType extends ArrayType
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
     * Represents the response object used to cancel a meeting.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarItemType[]
     */
    public $CancelCalendarItem = array();

    /**
     * Represents a Decline reply to a meeting request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeclineItemType[]
     */
    public $DeclineItem = array();

    /**
     * Contains an Exchange store item to forward to recipients.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ForwardItemType[]
     */
    public $ForwardItem = array();

    /**
     * Contains a reply to a post item.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\PostReplyItemType[]
     */
    public $PostReplyItem = array();

    /**
     * Specifies a response object that indicates that the meeting attendee can
     * propose a new meeting time.
     *
     * @since Exchange 2013 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ProposeNewTimeType[]
     */
    public $ProposeNewTime = array();

    /**
     * Removes an item from the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RemoveItemType[]
     */
    public $RemoveItem = array();

    /**
     * Contains a reply to all identified recipients of an item in the Exchange
     * store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyAllToItemType[]
     */
    public $ReplyAllToItem = array();

    /**
     * Contains a reply to the creator of an item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyToItemType[]
     */
    public $ReplyToItem = array();

    /**
     * Used to suppress read receipt requests.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SuppressReadReceiptType[]
     */
    public $SuppressReadReceipt = array();

    /**
     * Represents a Tentative reply to a meeting request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TentativelyAcceptItemType[]
     */
    public $TentativelyAcceptItem = array();
}
