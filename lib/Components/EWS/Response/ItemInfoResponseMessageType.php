<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single item operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class ItemInfoResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of created items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRealItemsType
     */
    public $Items;
}
