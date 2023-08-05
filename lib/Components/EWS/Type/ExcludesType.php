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

/**
 * Represents a bitwise mask of a specified property and a supplied value.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExcludesType extends SearchExpressionType
{
    /**
     * Represents a hexadecimal or decimal mask to be used during an Excludes
     * restriction operation.
     *
     * If the bitmask represents a hexadecimal number, it must be prefixed by 0x
     * or 0X. Otherwise, it will be considered a decimal number.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ExcludesValueType
     */
    public $Bitmask;
}
