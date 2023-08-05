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
 * Defines the recurrence pattern for recurring tasks.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Implement TaskRecurrencePatternTypes trait.
 * @todo Implement RecurrenceRangeTypes trait.
 */
class TaskRecurrenceType extends RecurrenceType
{
    /**
     * Describes how many days after the completion of the current task the next
     * occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DailyRegeneratingPatternType
     */
    public $DailyRegeneration;

    /**
     * Describes how many months after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\MonthlyRegeneratingPatternType
     */
    public $MonthlyRegeneration;

    /**
     * Describes how many weeks after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\WeeklyRegeneratingPatternType
     */
    public $WeeklyRegeneration;

    /**
     * Describes how many years after the completion of the current task the
     * next occurrence will be due.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\YearlyRegeneratingPatternType
     */
    public $YearlyRegeneration;
}
