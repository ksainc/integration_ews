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
 * Describes the type of a meeting request.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MeetingRequestTypeType extends Enumeration
{
    /**
     * Identifies the meeting request as a full update to an existing request.
     *
     * A full update has updated time and informational content.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FULL_UPDATE = 'FullUpdate';

    /**
     * Identifies the meeting request as only containing updated informational
     * content.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const INFORMATIONAL_UPDATE = 'InformationalUpdate';

    /**
     * Identifies the meeting request as a new meeting request.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NEW_MEETING_REQUEST = 'NewMeetingRequest';

    /**
     * Indicates that the meeting request type is not defined.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * Identifies the meeting request as outdated.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OUTDATED = 'Outdated';

    /**
     * Indicates that the meeting request belongs to a principal who has
     * forwarded meeting messages to a delegate and has his copies marked as
     * informational.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PRINCIPAL_WANTS_COPY = 'PrincipalWantsCopy';

    /**
     * Identifies the meeting request as a silent update to an existing meeting.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SILENT_UPDATE = 'SilentUpdate';
}
