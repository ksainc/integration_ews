<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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
 * Represents attendees and resources for a meeting.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttendeeType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $Mailbox = null, string $Response = null)
    {
        $this->Mailbox = $Mailbox;
        $this->ResponseType = $Response;
    }

    /**
     * Represents the date and time of the latest response that is received.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastResponseTime;

    /**
     * Identifies a fully resolved e-mail address.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;

    /**
     * Represents the type of recipient response that is received for a meeting.
     *
     * This property is only relevant to a meeting organizer's calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ResponseTypeType
     */
    public $ResponseType;
}
