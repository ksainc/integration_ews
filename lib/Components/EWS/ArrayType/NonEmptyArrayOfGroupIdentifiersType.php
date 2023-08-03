<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfGroupIdentifiersType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents a collection of Active Directory directory service group object
 * security identifiers.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayOfGroupIdentifiersType extends ArrayType
{
    /**
     * Represents a single security identifier and attribute for an Active
     * Directory object group of which the account is a member.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SidAndAttributesType[]
     */
    public $GroupIdentifier = array();
}
