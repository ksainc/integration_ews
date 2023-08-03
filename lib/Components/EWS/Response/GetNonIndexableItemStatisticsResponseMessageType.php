<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetNonIndexableItemStatisticsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetNonIndexableItemStatistics request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetNonIndexableItemStatisticsResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of statistics for items that could not be indexed.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfNonIndexableItemStatisticsType
     */
    public $NonIndexableItemStatistics;
}
