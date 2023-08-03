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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents the unique identities of items, occurrence items, and recurring
 * master items that are used to delete, send, get, move, or copy items in the
 * Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfBaseItemIdsType extends ArrayType
{
    
    public function __construct(array $i = null, array $o = null, array $ri = null, array $rr = null)
    {
        if (isset($i)) {$this->ItemId = $i;}
        if (isset($o)) {$this->OccurrenceItemId = $o;}
        if (isset($ri)) {$this->RecurringMasterItemId = $ri;}
        if (isset($rr)) {$this->RecurringMasterItemIdRanges = $rr;}
    }

    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType[]
     */
    public $ItemId = array();

    /**
     * Identifies a single occurrence of a recurring item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OccurrenceItemIdType[]
     */
    public $OccurrenceItemId = array();

    /**
     * Identifies a recurrence master item by identifying one of its related
     * occurrence items' identifiers.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringMasterItemIdType[]
     */
    public $RecurringMasterItemId = array();

    /**
     * Specifies an array of occurrence ranges.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringMasterItemIdRanges[]
     */
    public $RecurringMasterItemIdRanges = array();
}
