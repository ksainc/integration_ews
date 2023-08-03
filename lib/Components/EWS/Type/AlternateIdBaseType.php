<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AlternateIdBaseType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for alternate id types.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class AlternateIdBaseType extends Type
{
    /**
     * Describes the source or destination format in a request.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\IdFormatType
     */
    public $Format;
}
