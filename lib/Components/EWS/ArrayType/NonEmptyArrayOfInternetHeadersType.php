<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfInternetHeadersType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of some of the Internet message headers that are
 * contained in an item in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfInternetHeadersType extends ArrayType
{
    /**
     * Represents the Internet message header for a given header within the
     * headers collection.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\InternetHeaderType[]
     */
    public $InternetMessageHeader = array();
}
