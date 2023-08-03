<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RuleOperationErrorType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a rule operation error.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RuleOperationErrorType extends Type
{
    /**
     * Indicates the index of the operation in the request that caused the rule
     * operation error.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $OperationIndex;

    /**
     * Represents an array of rule validation errors on each rule field that has
     * an error.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRuleValidationErrorsType
     */
    public $ValidationErrors;
}
