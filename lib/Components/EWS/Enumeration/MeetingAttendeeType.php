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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the type of attendee that is identified in the Email element.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MeetingAttendeeType extends Enumeration
{
    /**
     * A mailbox user who is an optional attendee to the meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OPTIONAL = 'Optional';

    /**
     * The mailbox user and attendee who created the calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ORGANIZER = 'Organizer';

    /**
     * A mailbox user who is a required attendee to the meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const REQUIRED = 'Required';

    /**
     * A resource such as a TV or projector that is scheduled for use in the meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RESOURCE = 'Resource';

    /**
     * A mailbox entity that represents a room resource used for the meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ROOM = 'Room';
}
