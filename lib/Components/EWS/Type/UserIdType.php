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
 * Identifies a delegate user or a user who has folder access permissions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserIdType extends Type
{
    /**
     * Defines the display name of a folder, contact, distribution list, or
     * delegate user.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Identifies Anonymous and Default user accounts for delegate access.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DistinguishedUserType
     */
    public $DistinguishedUser;

    /**
     * Identifies an external delegate user or an external user who has folder
     * access permissions.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $ExternalUserIdentity;

    /**
     * Represents the primary Simple Mail Transfer Protocol (SMTP) address of an
     * account to be used for delegate access.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $PrimarySmtpAddress;

    /**
     * Represents the security descriptor definition language (SDDL) form of the
     * security identifier (SID).
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $SID;
}
