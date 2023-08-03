<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AggregateOnType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the property that is used to determine the order of grouped items
 * for a grouped FindItem result set.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AggregateOnType extends Type
{
    /**
     * Indicates the maximum or minimum value of the property identified by the
     * FieldURI element that is used for ordering the groups of items.
     *
     * The following are the possible values:
     * - Minimum
     * - Maximum
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\AggregateType
     */
    public $Aggregate;

    /**
     * Identifies extended MAPI properties to get, set, or create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType
     */
    public $ExtendedFieldURI;

    /**
     * Identifies frequently referenced properties by URI.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType
     */
    public $FieldURI;

    /**
     * Identifies individual members of a dictionary.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType
     */
    public $IndexedFieldURI;
}
