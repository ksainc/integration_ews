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
 * Represents a user's Out of Office (OOF) state.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class OofState extends Enumeration
{
    /**
     * The user's OOF setting is currently disabled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DISABLED = 'Disabled';

    /**
     * The user's OOF setting is currently enabled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ENABLED = 'Enabled';

    /**
     * The user's OOF setting is scheduled to start at a specific date and end
     * at another specific date.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SCHEDULED = 'Scheduled';
}
