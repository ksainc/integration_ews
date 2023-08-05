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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add a contact to a group based on the contacts phone
 * number.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class AddNewTelUriContactToGroupType extends BaseRequestType
{
    /**
     * Unique identifier of a group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;

    /**
     * Contains the SIP URI address of a contact that is added to an instant
     * messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ImContactSipUriAddress;

    /**
     * Represents the telephone number for a contact that is added to an instant
     * messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ImTelephoneNumber;

    /**
     * Contains the tel Uniform Resource Identifier (URI) for a contact.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $TelUriAddress;
}
