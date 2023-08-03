<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ExtendedPropertyType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies extended MAPI properties on folders and items.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExtendedPropertyType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(object $ExtendedFieldURI = null, string $Value = null)
    {
        $this->ExtendedFieldURI = $ExtendedFieldURI;
        $this->Value = $Value;
    }

    /**
     * Identifies an extended MAPI property to get, set, or create.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType
     */
    public $ExtendedFieldURI;

    /**
     * Contains the value of single-valued MAPI extended property.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Value;

    /**
     * Contains a collection of values for a multivalued extended MAPI property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPropertyValuesType
     */
    public $Values;
}
