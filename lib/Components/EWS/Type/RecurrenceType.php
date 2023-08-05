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
 * Defines the recurrence pattern for calendar items and meeting requests.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Implement RecurrencePatternTypes trait.
 * @todo Implement RecurrenceRangeTypes trait.
 */
class RecurrenceType extends Type
{
    /**
     * Represents a monthly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AbsoluteMonthlyRecurrencePatternType
     */
    public $AbsoluteMonthlyRecurrence;

    /**
     * Represents a yearly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AbsoluteYearlyRecurrencePatternType
     */
    public $AbsoluteYearlyRecurrence;

    /**
     * Describes the frequency, in days, in which a calendar item or task
     * recurs.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DailyRecurrencePatternType
     */
    public $DailyRecurrence;

    /**
     * Describes the start date and the end date of an item recurrence pattern.
     *
     * The use of this element excludes the use of the NoEndRecurrence and
     * NumberedRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EndDateRecurrenceRangeType
     */
    public $EndDateRecurrence;

    /**
     * Describes a recurrence pattern that does not have a defined end date.
     *
     * The use of this element excludes the use of the EndDateRecurrence and
     * NumberedRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NoEndRecurrenceRangeType
     */
    public $NoEndRecurrence;

    /**
     * Describes the start date and the number of occurrences of a recurring
     * item.
     *
     * The use of this element excludes the use of the NoEndRecurrence and
     * EndDateRecurrence elements.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NumberedRecurrenceRangeType
     */
    public $NumberedRecurrence;

    /**
     * Describes a relative monthly recurrence pattern for a recurring calendar
     * item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RelativeMonthlyRecurrencePatternType
     */
    public $RelativeMonthlyRecurrence;

    /**
     * Describes a relative yearly recurrence pattern.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RelativeYearlyRecurrencePatternType
     */
    public $RelativeYearlyRecurrence;

    /**
     * Describes the frequency, in weeks, and the days that a calendar item or
     * task recurs.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WeeklyRecurrencePatternType
     */
    public $WeeklyRecurrence;
}
