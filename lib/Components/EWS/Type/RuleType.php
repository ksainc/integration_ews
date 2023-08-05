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
 * Represents a single rule in a user's mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RuleType extends Type
{
    /**
     * Represents the actions to be taken on a message when the conditions are
     * fulfilled.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RuleActionsType
     */
    public $Actions;

    /**
     * Identifies the conditions that, when fulfilled, will trigger the rule
     * actions for a rule.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RulePredicatesType
     */
    public $Conditions;

    /**
     * Contains the display name of a rule.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Identifies the exceptions that represent all the available rule exception
     * conditions for the inbox rule.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RulePredicatesType
     */
    public $Exceptions;

    /**
     * Indicates whether the rule is enabled.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IsEnabled;

    /**
     * Indicates whether the rule is in an error condition.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IsInError;

    /**
     * Indicates whether the rule cannot be modified with the managed code APIs.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IsNotSupported;

    /**
     * Indicates the order in which a rule is to be run.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $Priority;

    /**
     * Specifies the rule identifier.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $RuleId;
}
