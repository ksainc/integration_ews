<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ServiceConfigurationType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies the requested service configurations by name.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ServiceConfigurationType extends Enumeration
{
    /**
     * Identifies the MailTips service configuration.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MAIL_TIPS = 'MailTips';

    /**
     * Identifies the Protection Rules service configuration.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const PROTECTION_RULES = 'ProtectionRules';

    /**
     * Identifies the Unified Messaging service configuration.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNIFIED_MESSAGING_CONFIG = 'UnifiedMessagingConfiguration';
}
