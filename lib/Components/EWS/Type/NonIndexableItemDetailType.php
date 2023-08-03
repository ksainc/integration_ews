<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\NonIndexableItemDetailType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines detail information about an item that cannot be indexed.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class NonIndexableItemDetailType extends Type
{
    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorCode;

    /**
     * Describes the error that is returned in information about an item that
     * cannot be indexed.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorDescription;

    /**
     * Indicates whether the item is partially indexed.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsPartiallyIndexed;

    /**
     * Indicates whether a previous attempt to index the item was unsuccessful.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsPermanentFailure;

    /**
     * Specifies a value used for sorting.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SortValue;

    /**
     * Represents the number of attempts that have been made to index the item.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $AttemptCount;

    /**
     * Contains the time and date at which the last attempt to index the item
     * was made.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastAttemptTime;

    /**
     * Specifies additional information about the hold status of a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $AdditionalInfo;
}
