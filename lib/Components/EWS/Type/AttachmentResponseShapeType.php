<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AttachmentResponseShapeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents additional properties to return in a response to a GetAttachment
 * request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttachmentResponseShapeType extends Type
{
    /**
     * Identifies additional properties to return in a response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     */
    public $AdditionalProperties;

    /**
     * Identifies how the body text is formatted in the response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeResponseType
     */
    public $BodyType;

    /**
     * Specifies whether potentially unsafe HTML content is filtered from an
     * attachment.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $FilterHtmlContent;

    /**
     * Specifies whether the Multipurpose Internet Mail Extensions (MIME)
     * content of an item or attachment is returned in the response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IncludeMimeContent;
}
