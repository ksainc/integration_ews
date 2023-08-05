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
 * Defines a request to get a list of mailboxes that the client has permission
 * to perform an eDiscovery search on.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetSearchableMailboxesType extends BaseRequestType
{
    /**
     * Indicates whether to expand the membership of the group.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $ExpandGroupMembership;

    /**
     * Contains the query string to filter the mailboxes to be returned.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SearchFilter;
}
