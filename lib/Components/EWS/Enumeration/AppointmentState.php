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
 * Specifies the status of the appointment.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class AppointmentState extends Enumeration
{
    /**
     * This appointment has been canceled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CANCELED = 4;

    /**
     * This appointment was forwarded.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FORWARD = 8;

    /**
     * This appointment is a meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MEETING = 1;

    /**
     * No flags have been set.
     *
     * This is only used for an appointment that does not include attendees.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NONE = 0;

    /**
     * This appointment has been received.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECEIVED = 2;
}
