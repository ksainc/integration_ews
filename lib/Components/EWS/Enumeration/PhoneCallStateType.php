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
 * Defines the current state for a phone call.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PhoneCallStateType extends Enumeration
{
    /**
     * The call is in alerting state (phone is ringing).
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ALERTED = 'Alerted';

    /**
     * The call is in the connected state.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CONNECTED = 'Connected';

    /**
     * The system is dialing this call.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CONNECTING = 'Connecting';

    /**
     * The call is disconnected.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DISCONNECTED = 'Disconnected';

    /**
     * The call is being forwarded to another destination.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FORWARDING = 'Forwarding';

    /**
     * Initial call state.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const IDLE = 'Idle';

    /**
     * The call is inbound.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INCOMING = 'Incoming';

    /**
     * The call is being transferred to another destination.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const TRANSFERRING = 'Transferring';
}
