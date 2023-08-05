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
 * Represents an account to impersonate when you are using the
 * ExchangeImpersonation SOAP header.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConnectingSIDType extends Type
{
    /**
     * Represents the primary Simple Mail Transfer Protocol (SMTP) address of
     * the account to use for Exchange impersonation.
     *
     * If the primary SMTP address is supplied, it will cost an extra Active
     * Directory directory service lookup in order to obtain the SID of the
     * user. We recommend that you use the SID or UPN if they are available.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $PrimarySmtpAddress;

    /**
     * Represents the user principal name (UPN) of the account to use for
     * impersonation.
     *
     * This should be the UPN for the domain where the user account exists.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $PrincipalName;

    /**
     * Represents the security descriptor definition language (SDDL) form of the
     * security identifier (SID) for the account to use for impersonation.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SID;

    /**
     * Represents the Simple Mail Transfer Protocol (SMTP) address of the
     * account to use for Exchange Impersonation.
     *
     * If the SMTP address is supplied, it will cost an extra Active Directory
     * lookup in order to obtain the SID of the user. We recommend that you use
     * the SID or UPN if they are available.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SmtpAddress;
}
