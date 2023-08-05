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
 * Defines the action performed on items with the retention tag.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class RetentionActionType extends Enumeration
{
    /**
     * The item is deleted and put into the Dumpster.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DELETE_AND_ALLOW_RECOVERY = 'DeleteAndAllowRecovery';

    /**
     * The item is marked as having exceeded the retention time limit.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MARK_AS_PAST_RETENTION_LIMIT = 'MarkAsPastRetentionLimit';

    /**
     * The item is moved to the archive mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MOVE_TO_ARCHIVE = 'MoveToArchive';

    /**
     * The item is moved to the default Deleted Items folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MOVE_TO_DELETED_ITEMS = 'MoveToDeletedItems';

    /**
     * The item is moved to a specified folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MOVE_TO_FOLDER = 'MoveToFolder';

    /**
     * No action is performed on the item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * The item is permanently deleted from the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PERMANENTLY_DELETE = 'PermanentlyDelete';
}
