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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines an array of conflict data.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class ArrayOfAttendeeConflictData extends ArrayType
{
    /**
     * Contains aggregate conflict information about the number of users
     * available, the number of users who have conflicts, and the number of
     * users who do not have availability information in a distribution list for
     * a suggested meeting time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\GroupAttendeeConflictData[]
     */
    public $GroupAttendeeConflictData = array();

    /**
     * Contains a user's or contact's free/busy status for a time window that
     * occurs at the same time as the suggested meeting time identified in the
     * Suggestion element.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IndividualAttendeeConflictData[]
     */
    public $IndividualAttendeeConflictData = array();

    /**
     * Represents an attendee that resolved as a distribution list that was too
     * large to expand.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TooBigGroupAttendeeConflictData[]
     */
    public $TooBigGroupAttendeeConflictData = array();

    /**
     * Represents an unresolvable attendee or an attendee that is not a user,
     * distribution list, or contact.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\UnknownAttendeeConflictData[]
     */
    public $UnknownAttendeeConflictData = array();
}
