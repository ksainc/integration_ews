<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPropertyValuesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of values for an extended property.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfPropertyValuesType extends ArrayType
{
    /**
     * Contains a value of an extended property.
     *
     * @since Exchange 2007
     *
     * @var string[]
     */
    public $Value = array();
}
