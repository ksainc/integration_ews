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
 * Represents a name and value pair of strings that is used to create properties
 * for message tracking reports.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TrackingPropertyType extends Type
{
    /**
     * Defines a name for the message tracking report property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $Name;

    /**
     * Defines a value for the message tracking report property.
     *
     * This element is optional.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $Value;
}
