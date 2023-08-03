<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetRoomListsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response from a GetRoomLists Operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetRoomListsResponseMessageType extends ResponseMessageType
{
    /**
     * Provides a list of e-mail addresses and display names that represent
     * lists of meeting rooms.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $RoomLists;
}
