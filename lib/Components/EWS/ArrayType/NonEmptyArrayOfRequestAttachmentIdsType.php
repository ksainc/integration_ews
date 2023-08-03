<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRequestAttachmentIdsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of attachment identifiers.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfRequestAttachmentIdsType extends ArrayType
{
    /**
     * The element that identifies a single attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RequestAttachmentIdType[]
     */
    public $AttachmentId = array();
}
