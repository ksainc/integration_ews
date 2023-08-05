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
 * Represents a meeting in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MeetingMessageType extends MessageType
{
    /**
     * Represents the calendar item that is associated with a MeetingMessage.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $AssociatedCalendarItemId;

    /**
     * Indicates the date and time that an instance of an iCalendar object was
     * created.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $DateTimeStamp;

    /**
     * Indicates whether a meeting message item has been processed.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $HasBeenProcessed;

    /**
     * Indicates whether a meeting was handled by an account with delegate
     * access.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsDelegated;

    /**
     * Indicates whether a meeting message is out-of-date.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsOutOfDate;

    /**
     * Used to identify a specific instance of a recurring calendar item.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $RecurrenceId;

    /**
     * Represents the type of recipient response received for a meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ResponseTypeType
     */
    public $ResponseType;

    /**
     * Identifies a calendar item.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $UID;
}
