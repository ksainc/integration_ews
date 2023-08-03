<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UpdateDelegateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to update delegates in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UpdateDelegateType extends BaseDelegateType
{
    /**
     * Contains an array of DelegateUser elements that identify the delegates
     * and the updates to apply to the delegates.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfDelegateUserType
     */
    public $DelegateUsers;

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
