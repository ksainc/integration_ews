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
 * Contains information about the subscription and the events that have occurred
 * since the last notification.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class NotificationType extends Type
{
    /**
     * Represents an event in which an item or folder is copied.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MovedCopiedEventType
     */
    public $CopiedEvent;

    /**
     * Represents an event in which an item or folder is created.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BaseObjectChangedEventType
     */
    public $CreatedEvent;

    /**
     * Represents an event in which an item or folder is deleted.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BaseObjectChangedEventType
     */
    public $DeletedEvent;

    /**
     * Represents an event in which an item’s free/busy time has changed.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\BaseObjectChangedEventType
     */
    public $FreeBusyChangedEvent;

    /**
     * Represents an event in which an item or folder is modified.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ModifiedEventType
     */
    public $ModifiedEvent;

    /**
     * Indicates whether there are more events in the queue to be delivered to
     * the client.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $MoreEvents;

    /**
     * Represents an event in which an item or folder is moved from one parent
     * folder to another parent folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MovedCopiedEventType
     */
    public $MovedEvent;

    /**
     * Represents an event that is triggered by a new mail item in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BaseObjectChangedEventType
     */
    public $NewMailEvent;

    /**
     * Represents the watermark of the latest event that was successfully
     * communicated to the client for the subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $PreviousWatermark;

    /**
     * Represents a notification that no new activity has occurred in the
     * mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BaseNotificationEventType
     */
    public $StatusEvent;

    /**
     * Represents the identifier for a subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SubscriptionId;
}
