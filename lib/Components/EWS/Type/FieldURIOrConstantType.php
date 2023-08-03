<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FieldURIOrConstantType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents either a property or a constant value to be used when comparing
 * with another property.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FieldURIOrConstantType extends SearchExpressionType
{
    /**
     * Identifies a constant value in a restriction.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ConstantValueType
     */
    public $Constant;
}
