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
 * Represents a reminder for a task or a calendar item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ReminderType extends Type
{
    /**
     * The end date of the item the reminder is for.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndDate;

    /**
     * The unique identifier of the reminder.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * The location for the item the reminder is for.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Location;

    /**
     * The unique identifier of the recurring master item for the reminder.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $RecurringMasterItemId;

    /**
     * Specified whether the reminder is for a calendar item or a task.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ReminderGroup
     */
    public $ReminderGroup;

    /**
     * The time for the reminder to occur.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ReminderTime;

    /**
     * The start date of the item the reminder is for.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartDate;

    /**
     * The subject of the item the reminder is for.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Subject;

    /**
     * The unique identifier of the calendar item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $UID;
}
