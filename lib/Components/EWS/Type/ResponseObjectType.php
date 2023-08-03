<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ResponseObjectType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Base type for reply objects.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class ResponseObjectType extends ResponseObjectCoreType
{
    /**
     * Item identifier that is related to a response object.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ReferenceItemId;
}
