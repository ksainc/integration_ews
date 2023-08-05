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
 * Identifies to whom external Out of Office (OOF) messages are sent..
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ExternalAudience extends Enumeration
{
    /**
     * E-mail senders outside the mailbox user's organization who send messages
     * to the user will receive an external OOF message response..
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * E-mail senders outside the mailbox user's organization who send messages
     * to the user will only receive an external OOF message response if the
     * sender is in the user's Exchange store contact list.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const KNOWN = 'Known';

    /**
     * E-mail senders outside the mailbox user's organization who send messages
     * to the user will not receive an external OOF message response.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NONE = 'None';
}
