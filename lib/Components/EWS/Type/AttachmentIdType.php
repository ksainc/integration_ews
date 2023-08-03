<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AttachmentIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies an item or file attachment.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttachmentIdType extends RequestAttachmentIdType
{
    /**
     * Identifies the unique identifier of the root store item to which the
     * attachment is attached.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $RootItemChangeKey;

    /**
     * Identifies the change key of the root store item to which the attachment
     * is attached.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $RootItemId;
}
