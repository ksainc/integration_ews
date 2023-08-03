<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ContextPropertyType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the context for an item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ContextPropertyType extends Type
{
    /**
     * Specifies the key of the context.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    public $Key;

    /**
     * Specifies the value of the context.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    public $Value;
}
