<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\ExportItemsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and results of a request to export a single mailbox
 * item.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class ExportItemsResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the item identifier of an exported item.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Contains the contents of an exported item.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $Data;
}
