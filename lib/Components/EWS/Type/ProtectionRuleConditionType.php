<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRuleConditionType.
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
