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
 * Represents availability information for a specific user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FreeBusyView extends Type
{
    /**
     * Contains a set of unique calendar item occurrences that represent the
     * requested user's availability.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfCalendarEvent
     */
    public $CalendarEventArray;

    /**
     * Represents the type of requested free/busy information returned in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\FreeBusyViewType
     */
    public $FreeBusyViewType;

    /**
     * Contains the merged free/busy stream of data.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $MergedFreeBusy;

    /**
     * Represents the time zone settings and working hours for the requested
     * mailbox user.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WorkingHours
     */
    public $WorkingHours;
}
