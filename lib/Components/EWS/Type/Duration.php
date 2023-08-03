<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\Duration.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a time span.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class Duration extends Type
{
    /**
     * Represents the end of the time span.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndTime;

    /**
     * Represents the start of the time span.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartTime;
}
