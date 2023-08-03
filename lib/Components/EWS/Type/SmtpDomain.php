<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SmtpDomain.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single SMTP domain.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SmtpDomain extends Type
{
    /**
     * Identifies the name of a domain.
     *
     * This attribute is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;

    /**
     * Indicates whether subdomains of the domain identified by the Name
     * attribute are considered internal.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IncludeSubdomains;
}
