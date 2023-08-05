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
 * Represents a single security identifier and attribute for an Active Directory
 * directory service object group of which the account is a member.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SidAndAttributesType extends Type
{
    /**
     * Contains group attributes.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Attributes;

    /**
     * Represents the security descriptor definition language (SDDL) form of a
     * security identifier (SID) that represents the group.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SecurityIdentifier;
}
