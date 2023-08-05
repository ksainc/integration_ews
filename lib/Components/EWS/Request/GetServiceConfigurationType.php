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
 * Defines a GetServiceConfiguration request.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetServiceConfigurationType extends BaseRequestType
{
    /**
     * Identifies who the caller is sending as.
     *
     * This element is optional.
     *
     * If this element is not present, the authenticated user is assumed to be
     * the sender. The ActingAs element must be included for requesting sender
     * hints. An ErrorInvalidArgument error can be returned in a response if the
     * ActingAs element is missing, does not include a routing type, does not
     * include an e-mail address, contains an invalid e-mail address, does not
     * resolve to a user in Active Directory Domain Services (AD DS), or
     * resolves to multiple users in AD DS.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $ActingAs;

    /**
     * Contains the requested service configurations.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfServiceConfigurationType
     */
    public $RequestedConfiguration;
}
