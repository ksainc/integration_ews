<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetRoomsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to get a list of rooms within a particular room list.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetRoomsType extends BaseRequestType
{
    /**
     * Represents an e-mail address that identifies a list of meeting rooms.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $RoomList;
}
