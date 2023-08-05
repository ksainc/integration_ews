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
 * Represents the permission level that a user has on a folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PermissionLevelType extends Enumeration
{
    /**
     * Indicates that the user can create and read all items in the folder, and
     * edit and delete only items that the user creates.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const AUTHOR = 'Author';

    /**
     * Indicates that the user can create items in the folder.
     *
     * The contents of the folder do not appear.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const CONTRIBUTOR = 'Contributor';

    /**
     * Indicates that the user has custom access permissions on the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const CUSTOM = 'Custom';

    /**
     * Indicates that the user can create, read, edit, and delete all items in
     * the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const EDITOR = 'Editor';

    /**
     * Indicates that the user can create and read all items in the folder, and
     * delete only items that the user creates.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const NON_EDITING_AUTHOR = 'NoneditingAuthor';

    /**
     * Indicates that the user has no permissions on the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * Indicates that the user can create, read, edit, and delete all items in
     * the folder, and create subfolders.
     *
     * The user is both folder owner and folder contact.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const OWNER = 'Owner';

    /**
     * Indicates that the user can create and read all items in the folder,
     * edit and delete only items that the user creates, and create subfolders.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const PUBLISHING_AUTHOR = 'PublishingAuthor';

    /**
     * Indicates that the user can create, read, edit, and delete all items in
     * the folder, and create subfolders.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const PUBLISHING_EDITOR = 'PublishingEditor';

    /**
     * Indicates that the user can read all items in the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const REVIEWER = 'Reviewer';
}
