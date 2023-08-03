<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetRoomsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response to a GetRooms operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetRoomsResponseMessageType extends ResponseMessageType
{
    /**
     * Provides a list of email addresses and display names that represent
     * meeting rooms.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRoomsType
     */
    public $Rooms;
}
