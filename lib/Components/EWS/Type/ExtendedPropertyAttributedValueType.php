<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ExtendedPropertyAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an extended property for a persona.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExtendedPropertyAttributedValueType extends Type
{
    /**
     * Specifies an array of attributions for its associated Value element.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfValueAttributionsType
     */
    public $Attributions;

    /**
     * Specifies an extended property for a persona.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ExtendedPropertyType
     */
    public $Value;
}
