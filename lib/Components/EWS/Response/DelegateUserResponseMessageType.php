<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\DelegateUserResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * contains the response message for a single delegate user.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class DelegateUserResponseMessageType extends ResponseMessageType
{
    /**
     * Identifies a single delegate that is returned in a delegate management
     * response.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\DelegateUserType
     */
    public $DelegateUser;
}
