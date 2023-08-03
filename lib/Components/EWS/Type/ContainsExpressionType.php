<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ContainsExpressionType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a search expression that determines whether a given property
 * contains the supplied constant string value.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ContainsExpressionType extends SearchExpressionType
{
    /**
     * Identifies a constant value in a restriction.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ConstantValueType
     */
    public $Constant;

    /**
     * Determines whether the search ignores cases and spaces.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ContainmentComparisonType
     */
    public $ContainmentComparison;

    /**
     * Identifies the boundaries of a search.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ContainmentModeType
     */
    public $ContainmentMode;
}
