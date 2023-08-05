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
 * Represents an offset from the time relative to Coordinated Universal Time
 * (UTC) that is represented by the Bias (UTC) element in regions where daylight
 * saving time is observed.
 *
 * This class also contains information about when the transition to daylight
 * saving time from standard time occurs.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SerializableTimeZoneTime extends Type
{
    /**
     * Represents the offset from the UTC offset that is identified by the Bias
     * (UTC) element for standard time and daylight saving time.
     *
     * This value is in minutes.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Bias;

    /**
     * Represents the day of the week when the transition to and from standard
     * time and daylight saving time occurs.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DayOfWeekType
     */
    public $DayOfWeek;

    /**
     * Represents the nth occurrence of the day that is specified in the
     * DayOfWeek element that represents the date of transition from and to
     * standard time and daylight saving time.
     *
     * The maximum value for this element can be either 4 or 5, depending on the
     * month and year.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $DayOrder;

    /**
     * Represents the transition month of the year to and from standard time and
     * daylight saving time.
     *
     * The value represents the ordinal rank of the month by occurrence and must
     * be a number between 1 and 12
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Month;

    /**
     * Represents the transition time of day to and from standard time and
     * daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Create a Time class that extends DateTime.
     */
    public $Time;

    /**
     * Used to define a time zone that changes depending on the year.
     *
     * The year format is YYYY.
     *
     * This element is optional.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $Year;
}
