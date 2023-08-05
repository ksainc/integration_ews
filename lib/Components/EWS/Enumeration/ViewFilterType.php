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
 * Defines the view filter type for a FindConversation operation.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ViewFilterType extends Enumeration
{
    /**
     * Find all conversations.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * For internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CLUTTER = 'Clutter';

    /**
     * Find flagged conversations.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const FLAGGED = 'Flagged';

    /**
     * Find conversations with attachments.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HAS_ATTACHMENT = 'HasAttachment';

    /**
     * For internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NO_CLUTTER = 'NoClutter';

    /**
     * Find active tasks.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASK_ACTIVE = 'TaskActive';

    /**
     * Find completed tasks.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASK_COMPLETED = 'TaskCompleted';

    /**
     * Find overdue tasks.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASK_OVERDUE = 'TaskOverdue';

    /**
     * Find conversations addressed or cc'd to me.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TO_OR_CC_ME = 'ToOrCcMe';

    /**
     * Find unread conversations.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const UNREAD = 'Unread';
}
