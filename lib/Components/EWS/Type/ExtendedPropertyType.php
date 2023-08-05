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
 * Identifies extended MAPI properties on folders and items.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExtendedPropertyType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $ExtendedFieldURI = null, string $Value = null)
    {
        $this->ExtendedFieldURI = $ExtendedFieldURI;
        $this->Value = $Value;
    }

    /**
     * Identifies an extended MAPI property to get, set, or create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType
     */
    public $ExtendedFieldURI;

    /**
     * Contains the value of single-valued MAPI extended property.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Value;

    /**
     * Contains a collection of values for a multivalued extended MAPI property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPropertyValuesType
     */
    public $Values;
}
