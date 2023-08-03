<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetAttachmentType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get an attachment from the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetAttachmentType extends BaseRequestType
{
    /**
     * Contains an array of attachment identifiers.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRequestAttachmentIdsType
     */
    public $AttachmentIds;

    /**
     * Identifies additional extended item properties to return in a response to
     * a GetAttachment request.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AttachmentResponseShapeType
     */
    public $AttachmentShape;
}
