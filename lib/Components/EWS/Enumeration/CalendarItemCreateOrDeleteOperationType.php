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
 * Describes how meeting requests are handled after they are created.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class CalendarItemCreateOrDeleteOperationType extends Enumeration
{
    /**
     * The meeting request is sent to all attendees but is not saved in the Sent
     * Items folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SEND_ONLY_TO_ALL = 'SendOnlyToAll';

    /**
     * The meeting request is sent to all attendees and a copy is saved in the
     * folder that is identified by the SavedItemFolderId element.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SEND_TO_ALL_AND_SAVE_COPY = 'SendToAllAndSaveCopy';

    /**
     * If the item is a meeting request, it is saved as a calendar item but not
     * sent.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SEND_TO_NONE = 'SendToNone';
}
