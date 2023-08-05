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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the request to move an item to the junk mail folder and to add the
 * sender to the blocked sender list.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class MarkAsJunkType extends BaseRequestType
{
    /**
     * Whether or not to add the sender to the blocked sender list.
     *
     * If false, and the user is already on the blocked sender list, they will
     * be removed.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsJunk;

    /**
     * Contains the unique identities of items to be marked as junk.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ItemIds;

    /**
     * Whether or not to move the item to the default junk mail folder.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $MoveItem;
}
