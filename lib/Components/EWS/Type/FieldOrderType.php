<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FieldOrderType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single field by which to sort results and indicates the
 * direction for the sort.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FieldOrderType extends Type
{
    /**
     * Identifies MAPI properties.
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

    /**
     * Describes the sort order direction.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SortDirectionType
     */
    public $Order;
}
