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
 * Represents the intended status for a calendar item that is associated with a
 * meeting request.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class LegacyFreeBusyType extends Enumeration
{
    /**
     * The calendar item represents busy time.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BUSY = 'Busy';

    /**
     * The calendar item represents free time.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FREE = 'Free';

    /**
     * The calendar item's status is not defined.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NO_DATA = 'NoData';

    /**
     * The calendar item represents time out of the office.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OUT_OF_OFFICE = 'OOF';

    /**
     * The calendar item represents tentatively busy time.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const TENTATIVE = 'Tentative';

    /**
     * The calendar item represents time working off site.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const WORKING_ELSEWHERE = 'WorkingElsewhere';
}
