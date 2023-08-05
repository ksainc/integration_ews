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
 * Represents a subscription to a streaming event notification subscription.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class StreamingSubscriptionRequest extends Type
{
    /**
     * Contains a collection of event notifications that are used to create a
     * subscription.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationEventTypesType
     */
    public $EventTypes;

    /**
     * Contains an array of folder identifiers that are used to identify folders
     * to monitor for event notifications.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FolderIds;

    /**
     * Indicates whether the server will subscribe to all folders in the user's
     * mailbox.
     *
     * A value of true indicates that the server will subscribe.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $SubscribeToAllFolders;
}
