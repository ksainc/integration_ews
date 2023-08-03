<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies frequently referenced properties by URI.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PathToUnindexedFieldType extends BasePathToElementType
{
    /*Constructor method with arguments*/
    public function __construct(string $uri = null)
    {
        $this->FieldURI = $uri;
    }
    
    /**
     * Identifies the URI of the property.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\UnindexedFieldURIType
     */
    public $FieldURI;
}
