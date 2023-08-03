<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindMessageTrackingReportResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single FindMessageTrackingReport
 * Operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FindMessageTrackingReportResponseMessageType extends ResponseMessageType
{
    /**
     * Contains information that will be used to produce various statistical
     * reports for the tracking feature in a DataCenter.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $Diagnostics;

    /**
     * Contains a property bag to store errors that are returned through the Web
     * service.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfArraysOfTrackingPropertiesType
     */
    public $Errors;

    /**
     * Contains the scope of the search that was performed to get the search results.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $ExecutedSearchScope;

    /**
     * Contains and array of messages that match the search requirements.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFindMessageTrackingSearchResultType
     */
    public $MessageTrackingSearchResults;

    /**
     * Contains a list of one or more tracking properties.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;
}
