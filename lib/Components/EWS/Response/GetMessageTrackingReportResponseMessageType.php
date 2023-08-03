<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetMessageTrackingReportResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response for the GetMessageTrackingReport operation.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetMessageTrackingReportResponseMessageType extends ResponseMessageType
{
    /**
     * Contains a single message that is returned in a GetMessageTrackingReport
     * operation.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\MessageTrackingReportType
     */
    public $MessageTrackingReport;

    /**
     * Provides timing and performance information that is used for reporting in
     * a DataCenter.
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
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfArraysOfTrackingPropertiesType
     */
    public $Errors;

    /**
     * Contains a list of one or more tracking properties.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;
}
