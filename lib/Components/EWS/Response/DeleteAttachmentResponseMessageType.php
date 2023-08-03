<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\DeleteAttachmentResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single DeleteAttachment operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class DeleteAttachmentResponseMessageType extends ResponseMessageType
{
    /**
     * Identifies the parent item of a deleted attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RootItemIdType
     */
    public $RootItemId;
}
