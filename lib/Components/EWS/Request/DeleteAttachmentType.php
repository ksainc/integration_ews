<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\DeleteAttachmentType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to delete an attachment from the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DeleteAttachmentType extends BaseRequestType
{
    /**
     * Contains an array of attachment identifiers that are used to delete the
     * attachments.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRequestAttachmentIdsType
     */
    public $AttachmentIds;
}
