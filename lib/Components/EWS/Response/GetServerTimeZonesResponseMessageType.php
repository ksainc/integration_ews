<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetServerTimeZonesResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single GetServerTimeZones operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetServerTimeZonesResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of time zone definitions.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTimeZoneDefinitionType
     */
    public $TimeZoneDefinitions;
}
