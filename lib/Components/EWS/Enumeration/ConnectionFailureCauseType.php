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
 * Specifies the reason for a disconnection from a telephone call.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConnectionFailureCauseType extends Enumeration
{
    /**
     * The called party did not answer.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const NO_ANSWER = 'NoAnswer';

    /**
     * Call state is not disconnected or the disconnect reason is not known.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * Catch-all for other disconnect reasons.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const OTHER = 'Other';

    /**
     * The called party number was not available.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNAVAILABLE = 'Unavailable';

    /**
     * The called party line was busy.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const USER_BUSY = 'UserBusy';
}
