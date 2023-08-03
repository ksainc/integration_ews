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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of data structures that a client can use to authorize
 * the sharing of its calendar or contact data with other clients.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class ArrayOfEncryptedSharedFolderDataType extends ArrayType
{
    /**
     * Contains the encrypted data that a client can use to authorize the
     * sharing of its calendar or contact data with other clients.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EncryptedSharedFolderDataType[]
     */
    public $EncryptedSharedFolderData = array();
}
