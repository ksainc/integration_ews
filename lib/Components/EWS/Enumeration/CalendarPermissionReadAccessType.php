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

/**
 * Indicates whether a user has permission to read items within a Calendar
 * folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class CalendarPermissionReadAccessType extends PermissionReadAccessType
{
    /**
     * Indicates that the user has permission to view only free/busy time in the\
     * calendar.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const TIME_ONLY = 'TimeOnly';

    /**
     * Indicates that the user has permission to view free/busy time in the
     * calendar and the subject and location of appointments.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const TIME_SUBJECT_AND_LOCATION = 'TimeAndSubjectAndLocation';
}
