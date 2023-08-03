<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\CreateRuleOperationType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an operation to create a new Inbox rule.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CreateRuleOperationType extends RuleOperationType
{
    /**
     * Represents a rule to be created in a user's mailbox.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\RuleType
     */
    public $Rule;
}
