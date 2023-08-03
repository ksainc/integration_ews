<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RuleValidationErrorType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single validation error on a particular rule property value,
 * predicate property value, or action property value.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RuleValidationErrorType extends Type
{
    /**
     * Represents a rule validation error code that describes what failed
     * validation for each rule predicate or action.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RuleValidationErrorCodeType
     */
    public $ErrorCode;

    /**
     * Represents the reason for the validation error.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $ErrorMessage;

    /**
     * Specifies the URI to the rule field that caused the validation error.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RuleFieldURIType
     */
    public $FieldUri;

    /**
     * Represents the value of the field that caused the validation error.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $FieldValue;
}
