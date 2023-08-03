<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ReferenceItemResponseType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Base class for responding to items with a reference.
 *
 * ObjectName property is prohibited.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ReferenceItemResponseType extends ResponseObjectType
{
    /**
     * {@inheritdoc}
     *
     * @prohibited
     */
    public $ObjectName;
}
