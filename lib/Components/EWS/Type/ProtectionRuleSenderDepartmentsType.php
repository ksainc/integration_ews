<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRuleSenderDepartmentsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies that the department of the sender matches any of the specified
 * departments in the child Value (ProtectionRuleValueType) elements.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleSenderDepartmentsType extends Type
{
    /**
     * Identifies a single sender department.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Value;
}
