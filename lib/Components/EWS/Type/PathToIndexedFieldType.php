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

/**
 * Identifies individual members of a dictionary.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PathToIndexedFieldType extends BasePathToElementType
{
    /*Constructor method with arguments*/
    public function __construct(string $uri, string $index = null)
    {
        $this->FieldURI = $uri;
        $this->FieldIndex = $index;
    }
 
    /**
     * Identifies the member of the dictionary to return.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $FieldIndex;

    /**
     * FieldURI property
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DictionaryURIType
     */
    public $FieldURI;
}
