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

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a hexadecimal or decimal mask to be used during an Excludes
 * restriction operation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExcludesValueType extends Type
{
    /**
     * Represents a decimal or hexadecimal bitmask.
     *
     * The value is represented by the following regular expression:
     * ((0x|0X)[0-9A-Fa-f]*)|([0-9]*).
     *
     * The following are examples of hexadecimal values for this attribute:
     * - 0x12AF
     * - 0X334AE
     *
     * The following are examples of decimal values for this attribute:
     * - 10
     * - 255
     * - 4562
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Determine if we need an ExcludesAttributeType class.
     */
    public $Value;
}
