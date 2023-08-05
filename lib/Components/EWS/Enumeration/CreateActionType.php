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
 * Defines the action for uploading an item into a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class CreateActionType extends Enumeration
{
    /**
     * Indicates that a new copy of the original item is uploaded to the
     * mailbox.
     *
     * The ItemId element must not be present if the CreateNew value is used.
     * The new item identifier is returned in the response.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const CREATE = 'CreateNew';

    /**
     * Specifies that the item indicated by the ItemId element will be updated.
     *
     * An error is returned if the ItemId element is not present or if the item
     * does not exist in the folder identified by the ParentFolderId element.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const UPDATE = 'Update';

    /**
     * Indicates that an attempt is first made to update the item.
     *
     * If the item does not exist in the folder specified by the ParentFolderId
     * element, a new item is created.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const UPDATE_OR_CREATE = 'UpdateOrCreate';
}
