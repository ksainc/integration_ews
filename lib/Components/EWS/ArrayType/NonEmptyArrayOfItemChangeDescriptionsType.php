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
 * Represents a set of elements that define append, set, and delete changes to
 * item properties.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfItemChangeDescriptionsType extends ArrayType
{
    /*Constructor method with arguments*/
    public function __construct(array $Append = null, array $Set = null, array $Delete = null)
    {
        if ($Append) {$this->AppendToItemField = $Append;}
        if ($Set) {$this->SetItemField = $Set;}
        if ($Delete) {$this->DeleteItemField = $Delete;}
    }
        
    /**
     * Represents data to append to a single property of an item during an
     * UpdateItem operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AppendToItemFieldType[]
     */
    public $AppendToItemField = array();

    /**
     * Represents an operation to delete a given property from an item during an
     * UpdateItem operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeleteItemFieldType[]
     */
    public $DeleteItemField = array();

    /**
     * Represents an update to a single property of an item in an UpdateItem
     * operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SetItemFieldType[]
     */
    public $SetItemField = array();
}
