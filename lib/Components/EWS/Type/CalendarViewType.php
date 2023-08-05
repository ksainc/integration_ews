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
 * Defines a FindItem operation as returning calendar items in a set as they
 * appear in a calendar.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarViewType extends BasePagingType
{
    /**
     * Identifies the end of a time span queried for calendar items.
     *
     * All calendar items that have a start time that is on or after EndDate
     * will not be returned. The value of EndDate can be specified in UTC
     * format, as in 2006-02-02T12:00:00Z, or in a format where local time and
     * time zone offset is specified, as in 2006-02-02T04:00:00-08:00.
     *
     * EndDate must be greater than or equal to StartDate; otherwise an error is
     * returned.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndDate;

    /**
     * Identifies the start of a time span queried for calendar items.
     *
     * All calendar items that have an end time that is before StartDate will
     * not be returned. The value of StartDate can be specified in coordinated
     * universal time (UTC) format, as in 2006-01-02T12:00:00Z, or in a format
     * where local time and time zone offset is specified, as in
     * 2006-01-02T04:00:00-08:00.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartDate;
}
