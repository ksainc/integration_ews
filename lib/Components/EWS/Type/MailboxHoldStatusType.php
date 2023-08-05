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
 * Defines the hold status of a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxHoldStatusType extends Type
{
    /**
     * Specifies additional information about the hold status of a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $AdditionalInfo;

    /**
     * Contains the identifier for a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Mailbox;

    /**
     * Specifies the hold status for a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\HoldStatusType
     */
    public $Status;
}
