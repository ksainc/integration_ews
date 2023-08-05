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
 * Represents an individual mailbox user and options for the type of data to be
 * returned about the mailbox user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxData extends Type
{
    /**
     * Represents the type of attendee identified in the Email property.
     *
     * This is used in requests for meeting suggestions.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MeetingAttendeeType
     */
    public $AttendeeType;

    /**
     * Represents the mailbox user for a GetUserAvailability query.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Email;

    /**
     * Specifies whether to return suggested times for calendar times that
     * conflict among the attendees.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $ExcludeConflicts;
}
