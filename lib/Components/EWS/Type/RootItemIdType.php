<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RootItemIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies the root item of a deleted item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RootItemIdType extends BaseItemIdType
{
    /**
     * Identifies the new change key of the root item of a deleted item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $RootItemChangeKey;

    /**
     * Identifies the root item of a deleted item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $RootItemId;
}
