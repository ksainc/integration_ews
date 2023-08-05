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
 * Represents additional information about a calendar event.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarEventDetails extends Type
{
    /**
     * Represents the entry ID of the calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ID;

    /**
     * Indicates whether an instance of a recurring calendar item is changed
     * from the master.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsException;

    /**
     * Indicates whether the calendar event is a meeting or an appointment.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsMeeting;

    /**
     * Indicates whether the calendar item is private.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsPrivate;

    /**
     * Indicates whether the calendar event is an instance of a recurring
     * calendar item or a single calendar item.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsRecurring;

    /**
     * Indicates whether a reminder has been set for the calendar event.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsReminderSet;

    /**
     * Represents the location field of the calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Location;

    /**
     * Represents the subject of the calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Subject;
}
