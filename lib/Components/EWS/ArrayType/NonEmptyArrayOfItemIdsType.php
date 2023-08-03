<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemIdsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents an array of item ids.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfItemIdsType extends ArrayType
{
    /*Constructor method with arguments*/
    public function __construct(array $ItemIds = null)
    {
        if ($ItemIds) {$this->ItemId = $ItemIds;}
    }
    
    /**
     * Specifies the unique identifier and change key of an item in the Exchange
     * store.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType[]
     */
    public $ItemId = array();
}
