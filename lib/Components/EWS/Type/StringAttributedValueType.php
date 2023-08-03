<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\StringAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents an attribute associated with a persona element.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class StringAttributedValueType extends Type
{
    /**
     * Specifies an array of attributions for the Value element.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfValueAttributionsType
     */
    public $Attributions;

    /**
     * Contains the value of the property.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Value;
}
