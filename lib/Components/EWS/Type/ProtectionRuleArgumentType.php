<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRuleArgumentType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies an argument to be passed to an action.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleArgumentType extends Type
{
    /**
     * A non-empty string value that represents the value of an argument to the
     * action part of a protection rule.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Value;
}
