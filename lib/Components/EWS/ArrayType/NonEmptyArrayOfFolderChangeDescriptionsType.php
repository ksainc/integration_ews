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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a set of elements that define append, set, and delete changes to
 * folder properties.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfFolderChangeDescriptionsType extends ArrayType
{
    /**
     * Represents data to append to a folder property during an UpdateFolder
     * operation.
     *
     * This property is not implemented and should not be used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AppendToFolderFieldType[]
     */
    public $AppendToFolderField = array();

    /**
     * Represents an operation to delete a given property from a folder during
     * an UpdateFolder operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeleteFolderFieldType[]
     */
    public $DeleteFolderField = array();

    /**
     * Represents an update to a single property on a folder in an UpdateFolder
     * operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SetFolderFieldType[]
     */
    public $SetFolderField = array();
}
