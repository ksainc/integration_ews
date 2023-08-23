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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of time zone transitions.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class ArrayOfTransitionsType extends ArrayType
{
    /**
     * Represents a time zone transition. 
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TransitionType[]
     */
    public $Transition = array();
    
    /**
     * A time zone transition that occurs on a specific date and at a specific
     * time.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\AbsoluteDateTransitionType[]
     */
    public $AbsoluteDateTransition = array();

    /**
     * The unique identifier of the transitions group.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Id;

    /**
     * A time zone transition that occurs on a specified day of the year.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringDateTransitionType[]
     */
    public $RecurringDateTransition = array();

    /**
     * A time zone transition that occurs on the same day each year.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RecurringDayTransitionType[]
     */
    public $RecurringDayTransition = array();
}
