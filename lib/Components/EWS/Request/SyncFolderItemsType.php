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
 * Defines a request to synchronize items in an Exchange store folder.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SyncFolderItemsType extends BaseRequestType
{
    /**
     * Identifies items to skip during synchronization.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfBaseItemIdsType
     */
    public $Ignore;

    /**
     * Identifies the item properties and content to include in a
     * SyncFolderItems response.
     *
     * This element is required.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemResponseShapeType
     */
    public $ItemShape;

    /**
     * Describes the maximum number of changes that can be returned in a
     * synchronization response.
     *
     * This element is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MaxChangesReturned;

    /**
     * Represents the folder that contains the items to synchronize.
     *
     * This element is required.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $SyncFolderId;

    /**
     * Specifies whether just items or items and folder associated information
     * are returned in a synchronization response.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SyncFolderItemsScopeType
     */
    public $SyncScope;

    /**
     * Contains a base64-encoded form of the synchronization data that is
     * updated after each successful request.
     *
     * This is used to identify the synchronization state.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var string|null
     */
    public $SyncState;
}
