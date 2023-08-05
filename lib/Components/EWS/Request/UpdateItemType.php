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
 * Defines a request to update an item in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UpdateItemType extends BaseRequestType
{
    /**
     * Identifies the type of conflict resolution to try during an update.
     *
     * The default value is AutoResolve.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ConflictResolutionType
     */
    public $ConflictResolution;

    /**
     * Contains an array of ItemChange elements that identify items and the
     * updates to apply to the items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangesType[]
     */
    public $ItemChanges;

    /**
     * Describes how the item will be handled after it is updated.
     *
     * he MessageDisposition attribute is required for message items, including
     * meeting messages such as meeting cancellations, meeting requests, and
     * meeting responses.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MessageDispositionType
     */
    public $MessageDisposition;

    /**
     * Identifies the target folder for operations that update, send, and create
     * items in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $SavedItemFolderId;

    /**
     * Describes how meeting updates are communicated after a calendar item is
     * updated.
     *
     * This attribute is required for calendar items and calendar item
     * occurrences.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarItemUpdateOperationType
     */
    public $SendMeetingInvitationsOrCancellations;

    /**
     * Indicates whether read receipts for the updated item should be
     * suppressed.
     *
     * A value of true indicates that read receipts should be suppressed. A
     * value of false indicates that the read receipts will be sent to the
     * sender.
     *
     * This attribute is optional.
     *
     * @since Exchange 2013 SP1
     *
     * @var boolean
     */
    public $SuppressReadReceipts;
}
