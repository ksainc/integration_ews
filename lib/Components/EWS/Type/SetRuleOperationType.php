<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SetRuleOperationType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an operation to update an existing rule.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SetRuleOperationType extends RuleOperationType
{
    /**
     * Represents a rule in a user's mailbox.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RuleType
     */
    public $Rule;
}
