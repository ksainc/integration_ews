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
 * Defines the type of event a notification is for.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class NotificationEventTypeType extends Enumeration
{
    /**
     * Indicates the notification is for an item copied event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COPIED_EVENT = 'CopiedEvent';

    /**
     * Indicates the notification is for an item created event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CREATED_EVENT = 'CreatedEvent';

    /**
     * Indicates the notification is for an item deleted event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DELETED_EVENT = 'DeletedEvent';

    /**
     * Indicates the notification is for a free or busy change event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FREE_BUSY_CHANGED_EVENT = 'FreeBusyChangedEvent';

    /**
     * Indicates the notification is for an item modified event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MODIFIED_EVENT = 'ModifiedEvent';

    /**
     * Indicates the notification is for an item moved event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MOVED_EVENT = 'MovedEvent';

    /**
     * Indicates the notification is for a new mail event.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NEW_MAIL_EVENT = 'NewMailEvent';
}
