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
 * Defines the property types to get in a GetUserConfiguration operation.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class UserConfigurationPropertyType extends Enumeration
{
    /**
     * Specifies the identifier, dictionary, XML data, and binary data property
     * types.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * Specifies binary data property types.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const BINARY_DATA = 'BinaryData';

    /**
     * Specifies dictionary property types.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DICTIONARY = 'Dictionary';

    /**
     * Specifies the identifier property.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ID = 'Id';

    /**
     * Specifies XML data property types.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const XML_DATA = 'XmlData';
}
