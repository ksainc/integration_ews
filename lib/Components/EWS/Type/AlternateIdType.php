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

/**
 * Describes an identifier to convert in a request and the results of a
 * converted identifier in the response.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AlternateIdType extends AlternateIdBaseType
{
    /**
     * Describes the source identifier in a ConvertId Operation request and
     * describes the destination identifier in a ConvertId Operation response.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $Id;

    /**
     * Indicates whether the identifier represents an archived item or folder.
     *
     * A value of true indicates that the identifier represents an archived item
     * or folder. This attribute is optional.
     *
     * @since Exchange 2010 SP1
     *
     * @var boolean
     */
    public $IsArchive;

    /**
     * Describes the mailbox primary Simple Mail Transfer Protocol (SMTP)
     * address that contains the identifiers to translate.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $Mailbox;
}
