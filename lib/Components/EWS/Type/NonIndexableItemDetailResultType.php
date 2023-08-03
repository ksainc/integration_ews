<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonIndexableItemDetailResultType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the results of the GetNonIndexableItemDetails request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class NonIndexableItemDetailResultType extends Type
{
    /**
     * Specifies an array of mailboxes that failed on search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFailedSearchMailboxesType
     */
    public $FailedMailboxes;

    /**
     * Contains an array of item details for non-indexable items.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfNonIndexableItemDetailsType
     */
    public $Items;
}
