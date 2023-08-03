<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfMailboxQueriesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines a list of mailboxes and associated queries for discovery search.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfMailboxQueriesType extends ArrayType
{
    /**
     * Specifies a query and the scope of a discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\MailboxQueryType[]
     */
    public $MailboxQuery = array();
}
