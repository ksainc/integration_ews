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
 * Describes a single physical address for a contact item.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common EntryType class.
 * @todo Create a common TextEntryType class.
 */
class PhysicalAddressDictionaryEntryType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Key = null,
                                string $Street = null,
                                string $City = null,
                                string $State = null,
                                string $PostalCode = null,
                                string $CountryOrRegion = null)
    {
        $this->Key = $Key;
        $this->Street = $Street;
        $this->City = $City;
        $this->State = $State;
        $this->PostalCode = $PostalCode;
        $this->CountryOrRegion = $CountryOrRegion;
    }

    /**
     * Identifies a physical address type.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PhysicalAddressKeyType
     */
    public $Key;

    /**
     * Represents a street address for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Street;

    /**
     * Represents the city name that is associated with a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $City;

    /**
     * Represents the state of residence for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $State;

    /**
     * Represents the postal code for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $PostalCode;

    /**
     * Represents the country or region for a given physical address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $CountryOrRegion;
}
