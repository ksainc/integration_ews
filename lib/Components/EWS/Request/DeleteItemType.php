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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to delete an item from a mailbox in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DeleteItemType extends BaseRequestType
{
    /**
     * Describes whether a task instance or a task master is deleted by a
     * DeleteItem operation.
     *
     * This attribute is required when tasks are deleted.
     *
     * This attribute is optional when non-task items are deleted.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\AffectedTaskOccurrencesType
     */
    public $AffectedTaskOccurrences;

    /**
     * Describes how an item is deleted.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DisposalType
     */
    public $DeleteType;

    /**
     * Contains an array of items, occurrence items, and recurring master items
     * to delete from a mailbox in the Exchange store.
     *
     * The DeleteItem operation can be performed on any item type.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ItemIds;

    /**
     * Describes whether a calendar item deletion is communicated to attendees.
     *
     * This attribute is required when calendar items are deleted.
     *
     * This attribute is optional if non-calendar items are deleted.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarItemCreateOrDeleteOperationType
     */
    public $SendMeetingCancellations;

    /**
     * Indicates whether read receipts for the deleted item are suppressed.
     *
     * A value of true indicates that the read receipts are suppressed. A value
     * of false indicates that the read receipts are sent to the sender.
     *
     * This attribute is optional.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $SuppressReadReceipts;
}
