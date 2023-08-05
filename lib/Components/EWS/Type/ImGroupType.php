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
 * Defines an instant messaging group.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ImGroupType extends Type
{
    /**
     * Contains the display name of a new instant messaging group contact or
     * the display name of a new instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Specifies the instant messaging (IM) group identifier.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ExchangeStoreId;

    /**
     * Specifies an array of additional properties
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfExtendedPropertyType
     */
    public $ExtendedProperties;

    /**
     * Specifies the group class of an instant messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $GroupType;

    /**
     * Specifies the identifiers of the contacts that are part of the instant
     * messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemIdsType
     */
    public $MemberCorrelationKey;

    /**
     * Represents the Simple Mail Transfer Protocol (SMTP) address of an account
     * to be used for impersonation or a Simple Mail Transfer Protocol (SMTP)
     * recipient address of a calendar or contact sharing request.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SmtpAddress;
}
