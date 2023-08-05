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
 * Defines a request to resolve ambiguous names.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ResolveNamesType extends BaseRequestType
{
    /**
     * Identifies the property set returned for contacts.
     *
     * @since Exchange 2010 SP2
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType
     */
    public $ContactDataShape;

    /**
     * Contains an array of contact folder identifiers that would be searched if
     * the SearchScope attribute is set to ActiveDirectoryContacts, Contacts, or
     * ContactsActiveDirectory.
     *
     * The ParentFolderIds array can only contain a single contact folder
     * identifier. If the ParentFolderIds element is not present, the default
     * Contacts folder is searched.
     *
     * The folder identifier can be used for delegate access.
     *
     * Active Directory searches are performed by using access control lists
     * (ACLs). Some users might not have the rights to see some Active Directory
     * objects.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $ParentFolderIds;

    /**
     * Describes whether the full contact details for public contacts for a
     * resolved name are returned in the response.
     *
     * This attribute is required for public contacts. This value does not
     * affect private contacts and private distribution lists, for which ItemId
     * is always returned.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $ReturnFullContactData;

    /**
     * Identifies the order and scope for a ResolveNames search.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ResolveNamesSearchScopeType
     */
    public $SearchScope;

    /**
     * Contains the name of a contact or distribution list to resolve.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $UnresolvedEntry;
}
