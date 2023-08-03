<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\UploadItemsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * the status and results of a request to upload a single mailbox item.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class UploadItemsResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the item identifier of an uploaded item.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;
}
