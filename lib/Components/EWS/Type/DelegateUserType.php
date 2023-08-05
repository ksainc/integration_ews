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
 * Identifies a single delegate to add or update in a mailbox or a delegate
 * returned in a delegate management response.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DelegateUserType extends Type
{
    /**
     * Contains the delegate permission level settings.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\DelegatePermissionsType
     */
    public $DelegatePermissions;

    /**
     * Indicates whether a delegate receives copies of meeting-related messages
     * that are addressed to the principal.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $ReceiveCopiesOfMeetingMessages;

    /**
     * Identifies the delegate.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\UserIdType
     */
    public $UserId;

    /**
     * Indicates whether a delegate has permission to view private calendar
     * items in the principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $ViewPrivateItems;
}
