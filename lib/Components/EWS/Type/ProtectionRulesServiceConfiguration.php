<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ProtectionRulesServiceConfiguration.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents service configuration information for the protection rules
 * service.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ProtectionRulesServiceConfiguration extends ServiceConfiguration
{
    /**
     * Identifies the list of internal SMTP domains of the organization.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\SmtpDomainList
     */
    public $InternalDomains;

    /**
     * Specifies how often, in whole hours, the client should request protection
     * rules from the server.
     *
     * This attribute is required and its value must be an integer that is equal
     * to or greater than 1.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $RefreshInterval;

    /**
     * An array of protection rules.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfProtectionRulesType
     */
    public $Rules;
}
