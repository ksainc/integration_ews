<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\StringType;
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for string types.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class StringType extends Type
{
    /**
     * Value of the element.
     *
     * @var string
     */
    public $_;
}
