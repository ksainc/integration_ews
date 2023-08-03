<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\ServiceConfigurationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents service configuration settings.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class ServiceConfigurationResponseMessageType extends ResponseMessageType
{
    /**
     * Contains service configuration information for the mail tips service.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\MailTipsServiceConfiguration
     */
    public $MailTipsConfiguration;

    /**
     * Contains service configuration information for the protection rules
     * service.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ProtectionRulesServiceConfiguration
     */
    public $ProtectionRulesConfiguration;

    /**
     * Contains service configuration information for the Unified Messaging
     * service.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\UnifiedMessageServiceConfiguration
     */
    public $UnifiedMessagingConfiguration;
}
