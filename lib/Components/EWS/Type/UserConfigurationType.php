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
 * Defines a single user configuration object.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserConfigurationType extends Type
{
    /**
     * Contains binary data property content for a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $BinaryData;

    /**
     * Defines a set of dictionary property entries for a user configuration
     * object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationDictionaryType
     */
    public $Dictionary;

    /**
     * Defines the user configuration object item identifier.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Represents the name of a user configuration object.
     *
     * This element must be used when you create a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UserConfigurationNameType
     */
    public $UserConfigurationName;

    /**
     * Contains XML data property content for a user configuration object.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $XmlData;
}
