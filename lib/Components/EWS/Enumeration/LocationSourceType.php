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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies information about the origin of an associated postal address, for
 * example, a contact or a telephone book.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class LocationSourceType extends Enumeration
{
    /**
     * The information was obtained from a contact.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CONTACT = 'Contact';

    /**
     * The information was obtained from the device.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DEVICE = 'Device';

    /**
     * The information was obtained from location services.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const LOCATION_SERVICES = 'LocationServices';

    /**
     * There is no location source.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * The information was obtained from phonebook services.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PHONEBOOK_SERVICES = 'PhonebookServices';

    /**
     * The information was obtained from a resource.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const RESOURCE = 'Resource';
}
