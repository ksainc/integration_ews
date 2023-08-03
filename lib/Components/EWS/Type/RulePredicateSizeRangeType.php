<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RulePredicateSizeRangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies the minimum and maximum sizes that incoming messages must be in
 * order for the condition or exception to apply.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RulePredicateSizeRangeType extends Type
{
    /**
     * Specifies the maximum size that a message must be in order for the
     * condition or exception to apply.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $MaximumSize;

    /**
     * Specifies the minimum size that a message must be in order for the
     * condition or exception to apply.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $MinimumSize;
}
