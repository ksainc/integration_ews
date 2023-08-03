<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFolderChangeDescriptionsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a set of elements that define append, set, and delete changes to
 * folder properties.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfFolderChangeDescriptionsType extends ArrayType
{
    /**
     * Represents data to append to a folder property during an UpdateFolder
     * operation.
     *
     * This property is not implemented and should not be used.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AppendToFolderFieldType[]
     */
    public $AppendToFolderField = array();

    /**
     * Represents an operation to delete a given property from a folder during
     * an UpdateFolder operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeleteFolderFieldType[]
     */
    public $DeleteFolderField = array();

    /**
     * Represents an update to a single property on a folder in an UpdateFolder
     * operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SetFolderFieldType[]
     */
    public $SetFolderField = array();
}
