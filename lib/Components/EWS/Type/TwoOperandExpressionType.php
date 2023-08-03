<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TwoOperandExpressionType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Base class for search expressions with two operands.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class TwoOperandExpressionType extends SearchExpressionType
{
    /**
     * Represents either a property or a constant value to be used when
     * comparing with another property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FieldURIOrConstantType
     */
    public $FieldURIOrConstant;
}
