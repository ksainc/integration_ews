<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\InternetHeaderType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the Internet message header for a given header within a headers
 * collection.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Extend a string class.
 */
class InternetHeaderType extends Type
{
    /**
     * The value of the header.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $_;

    /**
     * Identifies the header name.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $HeaderName;
}
