<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PersonaResponseShapeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a set of properties for a persona.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PersonaResponseShapeType extends Type
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
     * Identifies the set of properties to return in an item or folder response.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType
     */
    public $BaseShape;
}
