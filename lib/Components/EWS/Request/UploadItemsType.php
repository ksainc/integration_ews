<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UploadItemsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to upload items into a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UploadItemsType extends BaseRequestType
{
    /**
     * Contains an array of items to upload into a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfUploadItemsType
     */
    public $Items;
}
