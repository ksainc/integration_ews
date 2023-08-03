<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetUserAvailabilityRequestType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the arguments used to obtain user availability information.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetUserAvailabilityRequestType extends BaseRequestType
{
    /**
     * Specifies the type of free/busy information returned in the response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FreeBusyViewOptionsType
     */
    public $FreeBusyViewOptions;

    /**
     * Contains a list of mailboxes to query for availability information.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfMailboxData
     */
    public $MailboxDataArray;

    /**
     * Contains the options for obtaining meeting suggestion information.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SuggestionsViewOptionsType
     */
    public $SuggestionsViewOptions;

    /**
     * Contains elements that identify time zone information.
     *
     * This element also contains information about the transition between
     * standard time and daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SerializableTimeZone
     */
    public $TimeZone;
}
