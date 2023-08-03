<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SmtpDomainList.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a list of internal SMTP domains of the organization.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SmtpDomainList extends Type
{
    /**
     * Identifies a single SMTP domain.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\SmtpDomain
     */
    public $Domain;
}
