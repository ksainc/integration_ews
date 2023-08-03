<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRuleRecipientIsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Specifies that any recipient of the e-mail message matches any of the
 * specified recipients in the child Value (ProtectionRuleValueType) elements.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRuleRecipientIsType extends Type
{
    /**
     * Identifies a recipient.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Value;
}
