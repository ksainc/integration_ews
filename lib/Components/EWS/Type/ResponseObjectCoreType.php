<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ResponseObjectCoreType;
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Internal abstract base type for reply objects.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class ResponseObjectCoreType extends MessageType
{
    /**
     * The name of this reply object class as an English string.
     *
     * The client application is required to translate it if it's running in a
     * different locale.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ObjectName;
}
