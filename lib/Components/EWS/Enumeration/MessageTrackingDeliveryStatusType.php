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
 * Defines the status for a message.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MessageTrackingDeliveryStatusType extends Enumeration
{
    /**
     * Specifies that the message was delivered to all of the specified
     * recipients.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DELIVERED = 'Delivered';

    /**
     * Specifies that the message is waiting for approval from a moderator.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const PENDING = 'Pending';

    /**
     * Specifies that the message was delivered and read by the recipients.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const READ = 'Read';

    /**
     * Specifies that the message was transferred to a server outside the search
     * scope.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const TRANSFERRED = 'Transferred';

    /**
     * Specifies that a message was not delivered.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNSUCCESSFUL = 'Unsuccessful';
}
