<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a set of elements that define append, set, and delete changes to
 * item properties.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfItemChangeDescriptionsType extends ArrayType
{
    /*Constructor method with arguments*/
    public function __construct(array $Append = null, array $Set = null, array $Delete = null)
    {
        if ($Append) {$this->AppendToItemField = $Append;}
        if ($Set) {$this->SetItemField = $Set;}
        if ($Delete) {$this->DeleteItemField = $Delete;}
    }
        
    /**
     * Represents data to append to a single property of an item during an
     * UpdateItem operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AppendToItemFieldType[]
     */
    public $AppendToItemField = array();

    /**
     * Represents an operation to delete a given property from an item during an
     * UpdateItem operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\DeleteItemFieldType[]
     */
    public $DeleteItemField = array();

    /**
     * Represents an update to a single property of an item in an UpdateItem
     * operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SetItemFieldType[]
     */
    public $SetItemField = array();
}
