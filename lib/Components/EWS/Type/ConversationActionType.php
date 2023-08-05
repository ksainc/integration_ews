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
 * Represents a single action to be applied to a single conversation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConversationActionType extends Type
{
    /**
     * Contains the action to perform on the conversation specified by the
     * ConversationId element.
     *
     * This element must be present.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ConversationActionTypeType
     */
    public $Action;

    /**
     * Contains a collection of strings that identify the categories to which
     * items in a conversation belong.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $Categories;

    /**
     * Indicates the folder that is targeted for actions that use folders.
     *
     * This element must be present when copying, deleting, moving, and setting
     * read state on conversation items in a target folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ContextFolderId;

    /**
     * Contains the identifier of the conversation that will have the action
     * specified by the Action element applied to items in the conversation.
     *
     * This element must be present.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ConversationId;

    /**
     * Contains the date and time that a conversation was last synchronized.
     *
     * This element must be present when trying to delete all items in a
     * conversation that were received up to the specified time.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ConversationLastSyncTime;

    /**
     * Indicates how items in a conversation are deleted.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DisposalType
     */
    public $DeleteType;

    /**
     * Indicates the destination folder for copy and move actions.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $DestinationFolderId;

    /**
     * Specifies a flag that enables deletion for all new items in a
     * conversation.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $EnableAlwaysDelete;

    /**
     * Indicates whether a message has been read.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $IsRead;

    /**
     * Indicates whether the response is sent as soon as the action starts
     * processing on the server or whether the response is sent after the action
     * has completed.
     *
     * This element must be present for the response to be sent asynchronous to
     * the requested action.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $ProcessRightAway;
}
