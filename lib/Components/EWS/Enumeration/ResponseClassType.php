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
 * Defines the status of a response.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ResponseClassType extends Enumeration
{
    /**
     * Describes a request that cannot be fulfilled.
     *
     * The following are examples of sources of errors:
     * - Invalid attributes or elements
     * - Attributes or elements that are out of range
     * - An unknown tag
     * - An attribute or element that is not valid in the context
     * - An unauthorized access attempt by any client
     * - A server-side failure in response to a valid client-side call
     * - Information about the error can be found in the ResponseCode and
     *   MessageText elements.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ERROR = 'Error';

    /**
     * Describes a request that is fulfilled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SUCCESS = 'Success';

    /**
     * Describes a request that was not processed.
     *
     * A warning may be returned if an error occurred while an item in the
     * request was processing and subsequent items could not be processed. The
     * following are examples of sources of warnings:
     * - The Exchange store is offline during the batch.
     * - Active Directory Domain Services (AD DS) is offline.
     * - Mailboxes were moved.
     * - The message database (MDB) is offline.
     * - A password is expired.
     * - A quota has been exceeded.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const WARNING = 'Warning';
}
