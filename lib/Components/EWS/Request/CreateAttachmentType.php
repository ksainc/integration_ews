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
 * Defines a request to create an attachment to an item in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class CreateAttachmentType extends BaseRequestType
{
    /**
     * Contains the items or files to attach to an item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfAttachmentsType
     */
    public $Attachments;

    /**
     * Identifies the parent Exchange store item that contains the created
     * attachment.
     *
     * The ParentItemId element must provide the ID of a real Exchange store
     * item. Real store items can be retrieved by using the GetItem operation;
     * attachments are retrieved by using the GetAttachment operation. An error
     * occurs if the ParentItemId is passed the ID of a file attachment. If the
     * ParentItemId represents the ID of an existing item attachment, the
     * CreateAttachment operation adds the new attachment to the existing
     * attachment.
     *
     * This element is required.
     *
     * The following item attachments can be created:
     * - Item
     * - Message
     * - CalendarItem
     * - Contact
     * - Task
     * - MeetingMessage
     * - MeetingRequest
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ParentItemId;
}
