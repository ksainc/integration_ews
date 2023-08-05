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
 * Defines a dictionary object's type.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class UserConfigurationDictionaryObjectTypesType extends Enumeration
{
    /**
     * Defines the object's type as a boolean.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const BOOLEAN = 'Boolean';

    /**
     * Defines the object's type as a byte.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const BYTE = 'Byte';

    /**
     * Defines the object's type as an array of bytes.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const BYTE_ARRAY = 'ByteArray';

    /**
     * Defines the object's type as a date and time.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DATE_TIME = 'DateTime';

    /**
     * Defines the object's type as a 32-bit integer.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INTEGER_32 = 'Integer32';

    /**
     * Defines the object's type as a 64-bit integer.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INTEGER_64 = 'Integer64';

    /**
     * Defines the object's type as a string.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const STRING = 'String';

    /**
     * Defines the object's type as an array of strings.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const STRING_ARRAY = 'StringArray';

    /**
     * Defines the object's type as an unsigned 32-bit integer.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNSIGNED_INTEGER_32 = 'UnsignedInteger32';

    /**
     * Defines the object's type as an unsigned 64-bit integer.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNSIGNED_INTEGER_64 = 'UnsignedInteger64';
}
