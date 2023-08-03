<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines additional properties for use in GetItem, UpdateItem, CreateItem,
 * FindItem, or FindFolder requests.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfPathsToElementType extends ArrayType
{
    /**
     * Identifies extended MAPI properties to get, set, or create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType[]
     */
    public $ExtendedFieldURI = array();

    /**
     * Identifies frequently referenced properties by URI.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType[]
     */
    public $FieldURI = array();

    /**
     * Identifies frequently referenced dictionary properties by URI.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType[]
     */
    public $IndexedFieldURI = array();
}
