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
 * Contains the action to perform on a conversation specified by a
 * ConversationId.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConversationActionTypeType extends Enumeration
{
    /**
     * The current items and new items in the conversation will automatically be
     * set with the categories identified by the Categories property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const ALWAYS_CATEGORIZE = 'AlwaysCategorize';

    /**
     * The current items and new items in the conversation will automatically be
     * deleted.
     *
     * The deletion mode is set by the DeleteType property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const ALWAYS_DELETE = 'AlwaysDelete';

    /**
     * The current items and new items in the conversation will automatically be
     * moved to the folder identified by the DestinationFolderId property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const ALWAYS_MOVE = 'AlwaysMove';

    /**
     * The current items in the conversation will be copied to the folder
     * identified by the DestinationFolderId property.
     *
     * Subsequent items in the conversation will not be copied.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const COPY = 'Copy';

    /**
     * The current items in the conversation will be deleted.
     *
     * Subsequent items in the conversation will not be deleted. The deletion
     * mode is set by the DeleteType property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const DELETE = 'Delete';

    /**
     * The current items in the conversation will be moved to the folder
     * identified by the DestinationFolderId property.
     *
     * Subsequent items in the conversation will not be moved.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const MOVE = 'Move';

    /**
     * The current items in the conversation will have their read state set.
     *
     * The read state is set by the IsRead property.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const SET_READ_STATE = 'SetReadState';
}
