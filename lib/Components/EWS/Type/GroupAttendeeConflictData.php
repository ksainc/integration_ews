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
 * Represents aggregate conflict information about the number of users who are
 * available, the number of users who have conflicts, and the number of users
 * who do not have availability information in a distribution list for a
 * suggested meeting time.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class GroupAttendeeConflictData extends AttendeeConflictData
{
    /**
     * Represents the number of users, resources, and rooms in a distribution
     * list.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumberOfMembers;

    /**
     * Represents the number of distribution list members who are available for
     * a suggested meeting time.
     *
     * This element represents members for whom the status is Free.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumberOfMembersAvailable;

    /**
     * Represents the number of distribution list members who have a conflict
     * with a suggested meeting time.
     *
     * This element represents members who have a Busy, OOF, or Tentative status.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumberOfMembersWithConflict;

    /**
     * Represents the number of group members who do not have published
     * free/busy data to compare to a suggested meeting time.
     *
     * This element represents members of a distribution list that is too large
     * or members who have No Data status.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumberOfMembersWithNoData;
}
