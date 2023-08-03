<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRuleType.
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
