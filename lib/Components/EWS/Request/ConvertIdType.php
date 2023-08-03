<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\ConvertIdType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to convert item and folder identifiers between supported
 * Exchange formats.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ConvertIdType extends BaseRequestType
{
    /**
     * Describes the identifier format that will be returned for all the
     * converted identifiers.
     *
     * The DestinationFormat is described by the IdFormatType.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\IdFormatType
     */
    public $DestinationFormat;

    /**
     * Contains the source identifiers to convert.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAlternateIdsType
     */
    public $SourceIds;
}
