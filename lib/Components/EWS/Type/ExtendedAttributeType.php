<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ExtendedAttributeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Internal use only.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ExtendedAttributeType extends Type
{
    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Name;

    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Value;
}
