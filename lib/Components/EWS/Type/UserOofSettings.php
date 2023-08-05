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

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the Out of Office (OOF) settings.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UserOofSettings extends Type
{
    /**
     * Contains the duration for which the OOF status is enabled if the OofState
     * element is set to Scheduled.
     *
     * If the OofState element is set to Enabled or Disabled, the value of this
     * element is ignored.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\Duration
     */
    public $Duration;

    /**
     * Contains a value that determines to whom external OOF messages are sent.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ExternalAudience
     */
    public $ExternalAudience;

    /**
     * Contains the OOF response sent to addresses outside the recipient's
     * domain or trusted domains.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyBody
     */
    public $ExternalReply;

    /**
     * Contains the OOF response sent to other users in the user's domain or
     * trusted domain.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ReplyBody
     */
    public $InternalReply;

    /**
     * Contains the user's OOF state.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\OofState
     */
    public $OofState;
}
