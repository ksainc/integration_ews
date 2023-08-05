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
 * Compound property for Managed Folder related information for Managed Folders.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ManagedFolderInformationType extends Type
{
    /**
     * Indicates whether a managed folder can be deleted by a customer.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $CanDelete;

    /**
     * Indicates whether a given managed folder can be renamed or moved by the
     * customer.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $CanRenameOrMove;

    /**
     * Contains the comment that is associated with a managed folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Comment;

    /**
     * Describes the total size of all the contents of a managed folder.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $FolderSize;

    /**
     * Indicates whether the managed folder has a quota.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $HasQuota;

    /**
     * Specifies the URL that will be the default home page for the managed
     * folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $HomePage;

    /**
     * Indicates whether the managed folder is the root for all managed folders.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsManagedFoldersRoot;

    /**
     * Contains the folder ID of the managed folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ManagedFolderId;

    /**
     * Indicates whether the managed folder comment must be displayed.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $MustDisplayComment;

    /**
     * Describes the storage quota for the managed folder.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $StorageQuota;
}
