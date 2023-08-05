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
 * Represents the type of mailbox that is represented by an e-mail address.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MailboxTypeType extends Enumeration
{
    /**
     * Represents a contact in a user's mailbox.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACT = 'Contact';

    /**
     * Represents a mail-enabled Active Directory object.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MAILBOX = 'Mailbox';

    /**
     * Represents a one-off member of a personal distribution list.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ONE_OFF = 'OneOff';

    /**
     * Represents a private distribution list in a user's mailbox.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PRIVATE_DISTRIBUTION_LIST = 'PrivateDL';

    /**
     * Represents a public distribution list.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PUBLIC_DISTRIBUTION_LIST = 'PublicDL';

    /**
     * Represents a public folder.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const PUBLIC_FOLDER = 'PublicFolder';

    /**
     * Represents an unknown type of mailbox.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNKNOWN = 'Unknown';
}
