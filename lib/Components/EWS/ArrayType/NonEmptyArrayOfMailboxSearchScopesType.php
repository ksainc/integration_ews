<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfMailboxSearchScopesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines a list of one or more mailboxes and associated search scopes for a
 * discovery search.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfMailboxSearchScopesType extends ArrayType
{
    /**
     * Specifies a mailbox and a search scope for a discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\MailboxSearchScopeType[]
     */
    public $MailboxSearchScope = array();
}
