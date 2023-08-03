<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PreviewItemResponseShapeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the requested property set to be returned in a discovery search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PreviewItemResponseShapeType extends Type
{
    /**
     * Identifies additional properties.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     */
    public $AdditionalProperties;

    /**
     * Specifies either the default preview with all properties returned or a
     * compact preview with fewer properties returned.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PreviewItemBaseShapeType
     */
    public $BaseShape;
}
