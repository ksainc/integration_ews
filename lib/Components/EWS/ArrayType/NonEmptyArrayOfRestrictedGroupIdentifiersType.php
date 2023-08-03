<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRestrictedGroupIdentifiersType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of restricted groups from a user's token.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfRestrictedGroupIdentifiersType extends ArrayType
{
    /**
     * Represents the group security identifier (SID) and attributes for a
     * restricted group.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SidAndAttributesType[]
     */
    public $RestrictedGroupIdentifier = array();
}
