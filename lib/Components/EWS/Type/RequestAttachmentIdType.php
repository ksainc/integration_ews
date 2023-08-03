<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RequestAttachmentIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies a single attachment.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RequestAttachmentIdType extends BaseItemIdType
{
    /*Constructor method with arguments*/
    public function __construct(string $Id = null)
    {
        $this->Id = $Id;
    }

    /**
     * Specifies the attachment identifier.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Id;
}
