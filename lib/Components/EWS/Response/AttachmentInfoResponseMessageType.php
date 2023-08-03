<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\AttachmentInfoResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single CreateAttachment operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class AttachmentInfoResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the items or files that are attached to an item in the Exchange
     * store.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfAttachmentsType
     */
    public $Attachments;
}
