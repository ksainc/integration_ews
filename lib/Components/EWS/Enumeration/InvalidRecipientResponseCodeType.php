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
 * Provides information about why a recipient is invalid.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class InvalidRecipientResponseCodeType extends Enumeration
{
    /**
     * Indicates that there was a problem obtaining a security token from the
     * token server.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CANNOT_OBTAIN_TOKEN_FROM_STS = 'CannotObtainTokenFromSTS';

    /**
     * Indicates that the error is not specified by another error response code.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const OTHER = 'OtherError';

    /**
     * Indicates that the secure token service that is used by the specified
     * recipient is unknown.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const RECIPIENT_ORG_FEDERATED_UNKNOWN_TOKEN_ISSUER = 'RecipientOrganizationFederatedWithUnknownTokenIssuer';

    /**
     * Indicates that a sharing relationship is not available with the
     * organization specified in the recipient's SMTP e-mail address.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const RECIPIENT_ORG_NOT_FEDERATED = 'RecipientOrganizationNotFederated';

    /**
     * Indicates that the system administrator has set a system policy that
     * blocks sharing with the specified recipient.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const SYSTEM_POLICY_BLOCKS_SHARING_WITH_RECIPIENT = 'SystemPolicyBlocksSharingWithThisRecipient';
}
