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
 * Defines the well-known property set IDs for extended MAPI properties.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DistinguishedPropertySetType extends Enumeration
{
    /**
     * Identifies the address property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ADDRESS = 'Address';

    /**
     * Identifies the appointment property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const APPOINTMENT = 'Appointment';

    /**
     * Identifies the calendar assistant property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CALENDAR_ASSISTANT = 'CalendarAssistant';

    /**
     * Identifies the common property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMMON = 'Common';

    /**
     * Identifies the Internet headers property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const INTERNET_HEADERS = 'InternetHeaders';

    /**
     * Identifies the meeting property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MEETING = 'Meeting';

    /**
     * Identifies the public strings property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PUBLIC_STRINGS = 'PublicStrings';

    /**
     * Identifies the sharing property set ID by name.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    const SHARING = 'Sharing';

    /**
     * Indicates a task.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const TASK = 'Task';

    /**
     * Identifies the unified messaging property set ID by name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const UNIFIED_MESSAGING = 'UnifiedMessaging';
}
