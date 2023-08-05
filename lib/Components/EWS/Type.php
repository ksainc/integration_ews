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

namespace OCA\EWS\Components\EWS;

/**
 * Base class for Exchange Web Service Types.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class Type
{
    /**
     * Clones any object properties on a type object when it is cloned. Allows
     * for a deep clone required when using object to represent data types when
     * making a SOAP call.
     */
    public function __clone()
    {
        // Iterate over all properties on the current object.
        foreach (get_object_vars($this) as $property => $value) {
            // If the value of the property is an object then clone it.
            if (is_object($value)) {
                $this->$property = clone $value;
            } elseif (is_array($value)) {
                // The value is an array that may use objects as values. Iterate
                // over the array and clone any values that are objects into a
                // new array.
                // For some reason, if we try to set $this->$property to an
                // empty array then update it as we go it ends up being empty.
                // If we use a new array that we then set as the value of
                // $this->$property all is well.
                $new_value = array();
                foreach ($value as $index => $array_value) {
                    $new_value[$index] = (is_object($array_value) ? clone $array_value : $array_value);
                }

                // Set the property to the new array.
                $this->$property = $new_value;
            }
        }
    }
}
