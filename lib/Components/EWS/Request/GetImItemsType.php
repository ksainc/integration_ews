<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetImItemsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get information about the specified instant messaging
 * groups and instant messaging contact personas.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetImItemsType extends BaseRequestType
{
    /**
     * Contains an array of contact item identifiers.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $ContactIds;

    /**
     * Identifies an array of instant messaging group identifiers.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType
     */
    public $GroupIds;

    /**
     * Contains the extended properties used for the Unified Contact Store
     * operations.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfExtendedFieldURIs
     */
    public $ExtendedProperties;
}
