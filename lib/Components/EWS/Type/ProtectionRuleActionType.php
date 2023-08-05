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
 * Identifies what action must be executed if the condition part of a rule
 * passes.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleActionType extends Type
{
    /**
     * Specifies arguments to the action.
     *
     * This property should be left empty if the specified action does not
     * require arguments to be specified. This property can include one or more
     * values if an action requires one or more arguments. The
     * RightsProtectMessage action will contain a single argument.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleArgumentType
     */
    public $Argument;

    /**
     * Identifies the name of the action.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;
}
