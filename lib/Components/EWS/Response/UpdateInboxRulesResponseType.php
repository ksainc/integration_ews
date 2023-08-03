<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\UpdateInboxRulesResponseType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to an UpdateInboxRules request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class UpdateInboxRulesResponseType extends ResponseMessageType
{
    /**
     * Represents an array of rule validation errors on each rule field that has
     * an error.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRuleOperationErrorsType
     */
    public $RuleOperationErrors;
}
