<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MailboxQueryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a query and the scope of a discovery search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailboxQueryType extends Type
{
    /**
     * Specifies a list of one or more mailboxes and associated search scopes
     * for a discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfMailboxSearchScopesType
     */
    public $MailboxSearchScopes;

    /**
     * Contains the search query for the hold.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Query;
}
