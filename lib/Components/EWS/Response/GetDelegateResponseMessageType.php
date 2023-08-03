<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetDelegateResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a GetDelegate operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetDelegateResponseMessageType extends BaseDelegateResponseMessageType
{
    /**
     * Defines how meeting requests are handled between the delegate and the
     * principal.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DeliverMeetingRequestsType
     */
    public $DeliverMeetingRequests;
}
