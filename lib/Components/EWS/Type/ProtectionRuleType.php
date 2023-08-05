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
 * Represents a single protection rule.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleType extends Type
{
    /**
     * Identifies what action must be executed if the condition part of the rule
     * matches.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleActionType
     */
    public $Action;

    /**
     * Identifies the condition that must be satisfied for the action part of
     * the rule to be executed.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleConditionType
     */
    public $Condition;

    /**
     * Identifies the name of the rule.
     *
     * This property is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;

    /**
     * Specifies the rule priority.
     *
     * This property is required with a minimum value of 1.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $Priority;

    /**
     * Specifies whether the rule is mandatory.
     *
     * If the rule is mandatory, this attribute value must be false
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $UserOverridable;
}
