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
 * Specifies the error message for a mailbox that failed on search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FailedSearchMailboxType extends Type
{
    /**
     * Specifies the error code of the mailbox that failed the search.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $ErrorCode;

    /**
     * Represents the reason for the validation error.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorMessage;

    /**
     * Specifies whether the mailbox is an archive.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsArchive;

    /**
     * Contains an identifier for the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Mailbox;
}
