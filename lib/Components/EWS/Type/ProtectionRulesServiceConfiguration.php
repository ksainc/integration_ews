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

/**
 * Represents service configuration information for the protection rules
 * service.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRulesServiceConfiguration extends ServiceConfiguration
{
    /**
     * Identifies the list of internal SMTP domains of the organization.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\SmtpDomainList
     */
    public $InternalDomains;

    /**
     * Specifies how often, in whole hours, the client should request protection
     * rules from the server.
     *
     * This attribute is required and its value must be an integer that is equal
     * to or greater than 1.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $RefreshInterval;

    /**
     * An array of protection rules.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfProtectionRulesType
     */
    public $Rules;
}
