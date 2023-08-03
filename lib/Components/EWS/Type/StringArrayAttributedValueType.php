<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\StringArrayAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an instance of an array of string data.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class StringArrayAttributedValueType extends Type
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
     * Contains the value of the extended property.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Value;
}
