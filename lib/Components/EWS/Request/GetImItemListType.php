<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetImItemListType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get a list of instant messaging groups and contacts.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetImItemListType extends BaseRequestType
{
    /**
     * Contains any extended properties used for the request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfExtendedFieldURIs
     */
    public $ExtendedProperties;
}
