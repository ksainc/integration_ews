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
 * Defines a configuration for eDiscovery search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DiscoverySearchConfigurationType extends Type
{
    /**
     * Specifies the identity of a hold that preserves the mailbox items.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $InPlaceHoldIdentity;

    /**
     * Identifies the culture to be used for the culture-specific format of date
     * ranges.
     *
     * It also specifies the language used in a search query.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Language;

    /**
     * Specifies the managing organization.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ManagedByOrganization;

    /**
     * Specifies the identifier of the search.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SearchId;

    /**
     * Specifies the name of an eDiscovery search query.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SearchQuery;

    /**
     * Contains a list of mailboxes.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSearchableMailboxesType
     */
    public $SearchableMailboxes;
}
