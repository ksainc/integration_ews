<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfUploadItemsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of items to upload into a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfUploadItemsType extends ArrayType
{
    /**
     * Represents a single item to upload into a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\UploadItemType[]
     */
    public $Item = array();
}
