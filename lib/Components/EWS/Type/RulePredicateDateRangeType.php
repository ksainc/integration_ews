<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RulePredicateDateRangeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies the date range within which incoming messages have to have been
 * received in order for the condition or exception to apply.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RulePredicateDateRangeType extends Type
{
    /**
     * Specifies the rule time period and indicates that the rule condition is
     * met before this value.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndDateTime;

    /**
     * Specifies the rule time period and indicates that the rule condition is
     * met after this value.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartDateTime;
}
