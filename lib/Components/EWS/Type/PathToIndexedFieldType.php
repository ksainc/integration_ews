<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PathToIndexedFieldType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies individual members of a dictionary.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PathToIndexedFieldType extends BasePathToElementType
{
    /*Constructor method with arguments*/
    public function __construct(string $uri, string $index = null)
    {
        $this->FieldURI = $uri;
        $this->FieldIndex = $index;
    }
 
    /**
     * Identifies the member of the dictionary to return.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $FieldIndex;

    /**
     * FieldURI property
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DictionaryURIType
     */
    public $FieldURI;
}
