<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfTimeZoneIdType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of time zone definition identifiers.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfTimeZoneIdType extends ArrayType
{
    /**
     * The element that identifies a single time zone definition.
     *
     * @since Exchange 2010
     *
     * @var string[]
     */
    public $Id = array();
}
