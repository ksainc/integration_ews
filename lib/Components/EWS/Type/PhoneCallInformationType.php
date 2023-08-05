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
 * Represents the state information for a phone call.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PhoneCallInformationType extends Type
{
    /**
     * Specifies the cause of a connection failure.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ConnectionFailureCauseType
     */
    public $ConnectionFailureCause;

    /**
     * Specifies the state for a phone call.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PhoneCallStateType
     */
    public $PhoneCallState;

    /**
     * Specifies the SIP response code.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $SIPResponseCode;

    /**
     * Specifies the SIP response text.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SIPResponseText;
}
