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
 * Determines whether a search ignores cases and spaces.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ContainmentComparisonType extends Enumeration
{
    /**
     * The comparison must be exact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EXACT = 'Exact';

    /**
     * The comparison ignores casing.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const IGNORE_CASE = 'IgnoreCase';

    /**
     * The comparison ignores non-spacing characters.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const IGNORE_CASE_AND_NON_SPACING_CHARS = 'IgnoreCaseAndNonSpacingCharacters';

    /**
     * The comparison ignores casing and non-spacing characters.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const IGNORE_NON_SPACING_CHARS = 'IgnoreNonSpacingCharacters';

    /**
     * To be removed.
     *
     * @since Exchange 2007
     * @deprecated Exchange 2007
     *
     * @var string
     */
    const LOOSE = 'Loose';

    /**
     * To be removed.
     *
     * @since Exchange 2007
     * @deprecated Exchange 2007
     *
     * @var string
     */
    const LOOSE_AND_IGNORE_CASE = 'LooseAndIgnoreCase';

    /**
     * To be removed.
     *
     * @since Exchange 2007
     * @deprecated Exchange 2007
     *
     * @var string
     */
    const LOOSE_AND_IGNORE_CASE_AND_IGNORE_NON_SPACING_CARS = 'LooseAndIgnoreCaseAndIgnoreNonSpace';

    /**
     * To be removed.
     *
     * @since Exchange 2007
     * @deprecated Exchange 2007
     *
     * @var string
     */
    const LOOSE_AND_IGNORE_NON_SPACING_CHARS = 'LooseAndIgnoreNonSpace';
}
