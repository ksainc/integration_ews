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
 * Defines a timezone.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TimeZoneDefinitionType extends Type
{
    /**
     * Unique identifier of the time zone definition.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Id;

    /**
     * Descriptive name of the time zone definition.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;

    /**
     * Array of Period elements that define the time offset at different stages
     * of the time zone.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPeriodsType
     */
    public $Periods;

    /**
     * Array of time zone transitions.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsType
     */
    public $Transitions;

    /**
     * Array of TransitionsGroup elements that specify time zone transitions.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTransitionsGroupsType
     */
    public $TransitionsGroups;
}
