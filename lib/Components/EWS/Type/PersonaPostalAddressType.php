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
 * Defines a postal address associated with a persona.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PersonaPostalAddressType extends Type
{
    /**
     * Specifies the accuracy of the latitude and longitude of the associated
     * postal address.
     *
     * @since Exchange 2013
     *
     * @var float
     */
    public $Accuracy;

    /**
     * Specifies the altitude of a postal address.
     *
     * @since Exchange 2013
     *
     * @var float
     */
    public $Altitude;

    /**
     * Specifies the accuracy of the altitude property for a postal address.
     *
     * @since Exchange 2013
     *
     * @var float
     */
    public $AltitudeAccuracy;

    /**
     * Represents the city name that is associated with a contact.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $City;

    /**
     * Identifies a country identifier in a postal address.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Country;

    /**
     * Specifies the formatted display value of the associated postal address.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $FormattedAddress;

    /**
     * Specifies the latitude of the location of the associated postal address.
     *
     * @since Exchange 2013
     *
     * @var float
     */
    public $Latitude;

    /**
     * Specifies information about the origin of the associated postal address,
     * for example, a contact or a telephone book.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\LocationSourceType
     */
    public $LocationSource;

    /**
     * Contains a string specifying a Uniform Resource Identifier (URI) of the
     * associated postal address.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $LocationUri;

    /**
     * Specifies the longitude of the location of the associated postal address.
     *
     * @since Exchange 2013
     *
     * @var float
     */
    public $Longitude;

    /**
     * Specifies the "post office box" portion of a postal address.
     *
     * @since Exchange 2013
     *
     * @var type
     */
    public $PostOfficeBox;

    /**
     * Represents the postal code for a contact item.
     *
     * @since Exchange 2013
     *
     * @var type
     */
    public $PostalCode;

    /**
     * Represents the state of residence for a contact item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $State;

    /**
     * Represents a street address for a contact item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Street;

    /**
     * Specifies the type of postal address or phone number.
     *
     * For example, "Home" or "Business".
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Type;
}
