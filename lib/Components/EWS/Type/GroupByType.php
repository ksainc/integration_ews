<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\GroupByType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines an arbitrary grouping for FindItem queries.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class GroupByType extends BaseGroupByType
{
    /**
     * Represents the field that is used to determine the order of groups in a
     * response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AggregateOnType
     */
    public $AggregateOn;

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
