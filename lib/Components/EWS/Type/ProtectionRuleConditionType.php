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
 * Identifies a condition that must be satisfied for the action part of a rule
 * to be executed.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleConditionType extends Type
{
    /**
     * Evaluates to true if all recipients of an e-mail message are internal to
     * the sender's organization.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $AllInternal;

    /**
     * Specifies that all child elements must match to evaluate to true.
     *
     * Specifies that there must be more than one protection rule child
     * condition.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleAndType
     */
    public $And;

    /**
     * Specifies that any recipient of the e-mail message matches any of the
     * specified recipients in the child Value (ProtectionRuleValueType)
     * elements.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleRecipientIsType
     */
    public $RecipientIs;

    /**
     * Specifies that the department of the sender matches any of the specified
     * departments in the child Value (ProtectionRuleValueType) elements.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRuleSenderDepartmentsType
     */
    public $SenderDepartments;

    /**
     * Specifies a condition that always matches.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $True;
}
