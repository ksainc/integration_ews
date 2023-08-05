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
 * Represents the date and time when a time change occurs.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TimeChangeType extends Type
{
    /**
     * Represents the date when the time changes from standard time or daylight
     * saving time.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a Date object that extends DateTime.
     */
    public $AbsoluteDate;

    /**
     * Describes the offset from the BaseOffset.
     *
     * Together with the BaseOffset element, the Offset element identifies
     * whether the time is standard time or daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Offset;

    /**
     * Describes a relative yearly recurrence pattern for a time zone transition
     * date.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType
     */
    public $RelativeYearlyRecurrence;

    /**
     * Describes the time when the time changes between standard time and
     * daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a Time object that extends DateTime.
     */
    public $Time;

    /**
     * Name of the time zone.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $TimeZoneName;
}
