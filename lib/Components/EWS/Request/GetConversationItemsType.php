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
 * Defines a request to get a set of items that are related by being in the same
 * conversation.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetConversationItemsType extends BaseRequestType
{
    /**
     * Contains an array of conversations to get items for.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfConversationsType
     */
    public $Conversations;

    /**
     * Identifies a list of folders that are ignored when getting items in a
     * conversation.
     *
     * All conversation items in the ignored folders are not returned in the
     * response.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $FoldersToIgnore;

    /**
     * Identifies a set of properties to return.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemResponseShapeType
     */
    public $ItemShape;

    /**
     * Identifies whether a search or fetch for a conversation should span
     * either the primary mailbox, archive mailbox, or both the primary and
     * archive mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailboxSearchLocationType
     */
    public $MailboxScope;

    /**
     * Identifies the maximum number of conversations items to return.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $MaxItemsToReturn;

    /**
     * Specifies the sort order used for the result.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ConversationNodeSortOrder
     */
    public $SortOrder;
}
