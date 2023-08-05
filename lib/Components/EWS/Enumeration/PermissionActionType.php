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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Indicates which items in a folder a user has permission to perform an action
 * on.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PermissionActionType extends Enumeration
{
    /**
     * Indicates that the user has permission to perform the action on all items
     * in the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * Indicates that the user does not have permission to perform the action on
     * items in the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * Indicates that the user has permission to perform the action on the items
     * that the user owns in the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const OWNED = 'Owned';
}
