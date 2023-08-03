<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\AddDelegateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add delegates to a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class AddDelegateType extends BaseDelegateType
{
    /**
     * Contains the identities of delegates to add to or update in a mailbox.
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
