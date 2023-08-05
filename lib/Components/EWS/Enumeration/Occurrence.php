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
 * Represents the occurrence of the day of the week in a month.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class Occurrence extends Enumeration
{
    /**
     * The first occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FIRST_FROM_BEGINNING = 1;

    /**
     * The first occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FIRST_FROM_END = -1;

    /**
     * The fourth occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FOURTH_FROM_BEGINNING = 4;

    /**
     * The fourth occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FOURTH_FROM_END = -4;

    /**
     * The second occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const SECOND_FROM_BEGINNING = 2;

    /**
     * The second occurrence of the specified day of the week from the end of
     * the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const SECOND_FROM_END = -2;

    /**
     * The third occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const THIRD_FROM_BEGINNING = 3;

    /**
     * The third occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const THIRD_FROM_END = -3;
}
