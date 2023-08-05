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
 * Base class for permission types.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class BasePermissionType extends Type
{
    /**
     * Indicates whether a user has permission to create items in a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $CanCreateItems;

    /**
     * Indicates whether a user has permission to create subfolders in a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $CanCreateSubFolders;

    /**
     * Indicates whether a user has permission to delete items in a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PermissionActionType
     */
    public $DeleteItems;

    /**
     * Indicates whether a user has permission to edit items in a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PermissionActionType
     */
    public $EditItems;

    /**
     * Indicates whether a user is a contact for a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $IsFolderContact;

    /**
     * Indicates whether a user is the owner of a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $IsFolderOwner;

    /**
     * Indicates whether a user can view a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $IsFolderVisible;

    /**
     * Identifies a delegate user or a user who has folders access permissions.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\UserIdType
     */
    public $UserId;
}
