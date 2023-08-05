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
 * Base class for Exchange Web Service Enumerations.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class Enumeration extends Type
{
    /**
     * Element value.
     *
     * @deprecated 1.0.0
     *   This property will be removed in a future release and should not be
     *   used. Instead, you should reference the constants implemented in the
     *   class directly.
     *
     * @var string
     */
    public $_;

    /**
     * Returns the value of this enumeration as a string..
     *
     * @return string
     *
     * @suppress PhanDeprecatedProperty
     */
    public function __toString()
    {
        return $this->_;
    }
}
