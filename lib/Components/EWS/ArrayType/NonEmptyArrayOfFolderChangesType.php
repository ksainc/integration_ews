<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFolderChangesType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of changes for a folder.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfFolderChangesType extends ArrayType
{
    /**
     * Represents a single change to be performed on a single folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderChangeType[]
     */
    public $FolderChange = array();
}
