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
 * Specifies the source format of the Id to be converted or the format of the Id
 * after conversion.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class IdFormatType extends Enumeration
{
    /**
     * Describes MAPI identifiers, as in the PR_ENTRYID property.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const ENTRY_ID = 'EntryId';

    /**
     * Describes identifiers that are produced by Exchange Web Services starting
     * with Exchange 2007 SP1.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const EWS_ID = 'EwsId';

    /**
     * Describes identifiers that are produced by Exchange Web Services in the
     * initial release version of Exchange 2007.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const EWS_LEGACY_ID = 'EwsLegacyId';

    /**
     * Describes a hexadecimal-encoded representation of the PR_ENTRYID
     * property.
     *
     * This is the format of availability calendar event identifiers.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const HEX_ENTRY_ID = 'HexEntryId';

    /**
     * Describes a Microsoft Office Outlook Web Access identifier.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const OWA_ID = 'OwaId';

    /**
     * Describes Exchange store identifiers.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const STORE_ID = 'StoreId';
}
