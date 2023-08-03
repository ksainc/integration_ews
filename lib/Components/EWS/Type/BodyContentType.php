<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BodyContentType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the value of a BodyContentAttributedValue element.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BodyContentType extends Type
{
    /**
     * Identifies how the body text is formatted.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeResponseType
     */
    public $BodyType;

    /**
     * Contains the value of the body content.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Value;
}
