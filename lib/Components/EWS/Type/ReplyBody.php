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
 * Defines the out of office (OOF) response that is sent to addresses outside
 * the recipient's domain or trusted domains.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ReplyBody extends Type
{
    /**
     * Specifies the language used in the ExternalReply message.
     *
     * The possible values for this attribute are defined by IETF RFC 3066.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $lang;

    /**
     * Contains the OOF response.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Message;
}
