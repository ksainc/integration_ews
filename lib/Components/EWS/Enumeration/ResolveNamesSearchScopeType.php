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
 * Defines the order and scope for a ResolveNames search.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ResolveNamesSearchScopeType extends Enumeration
{
    /**
     * Only the Active Directory directory service is searched.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ACTIVE_DIRECTORY = 'ActiveDirectory';

    /**
     * Active Directory is searched first, and then the contact folders that are
     * specified in the ParentFolderIds property are searched.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ACTIVE_DIRECTORY_CONTACTS = 'ActiveDirectoryContacts';

    /**
     * Only the contact folders that are identified by the ParentFolderIds
     * property are searched.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS = 'Contacts';

    /**
     * Contact folders that are identified by the ParentFolderIds property are
     * searched first and then Active Directory is searched.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_ACTIVE_DIRECTORY = 'ContactsActiveDirectory';
}
