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
 * Defines token serialization in server-to-server authentication.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SerializedSecurityContextType extends Type
{
    /**
     * Represents a collection of Active Directory directory service group
     * object security identifiers.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfGroupIdentifiersType
     */
    public $GroupSids;

    /**
     * Represents the primary Simple Mail Transfer Protocol (SMTP) address of an
     * account to be used for server-to-server authorization.
     *
     * @var string
     */
    public $PrimarySmtpAddress;

    /**
     * Represents the group security identifier and attributes for a restricted
     * group.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRestrictedGroupIdentifiersType
     */
    public $RestrictedGroupSids;

    /**
     * Represents the security descriptor definition language (SDDL) form of the
     * user security identifier in a serialized security context SOAP header.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $UserSid;
}
