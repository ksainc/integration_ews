<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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
 * Represents an item identifier and the updates to apply to the item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ItemChangeType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $Id = null, object $Updates = null)
    {
        $this->ItemId = $Id;
        $this->Updates = $Updates;
    }

    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * This element is required if the OccurrenceItemId or RecurringMasterItemId
     * element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Identifies a single occurrence of a recurring item.
     *
     * This element is required if used. This element is required if the
     * RecurringMasterItemId or ItemId element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceItemIdType
     */
    public $OccurrenceItemId;

    /**
     * Identifies a recurrence master item by identifying one of its related
     * occurrence items' identifiers.
     *
     * This element is required if used. This element is required if the
     * OccurrenceItemId or ItemId element is not used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringMasterItemIdType
     */
    public $RecurringMasterItemId;

    /**
     * Contains an array that defines append, set, and delete changes to item
     * properties.
     *
     * This element is required.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType
     */
    public $Updates;
}
