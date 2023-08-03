<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxSearchScopeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a mailbox and a search scope for a discovery search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxSearchScopeType extends Type
{
    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfExtendedAttributesType
     */
    public $ExtendedAttributes;

    /**
     * Contains an identifier for a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Mailbox;

    /**
     * Specifies a mailbox and a search scope for a discovery search.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailboxSearchLocationType
     */
    public $SearchScope;
}
