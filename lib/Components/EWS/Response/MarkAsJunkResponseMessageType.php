<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\MarkAsJunkResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response message for a MarkAsJunk request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class MarkAsJunkResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the identifier of the item that was moved.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $MovedItemId;
}
