<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DeleteRuleOperationType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an operation to delete an existing Inbox rule.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DeleteRuleOperationType extends RuleOperationType
{
    /**
     * Specifies the identifier of the rule to delete.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $RuleId;
}
