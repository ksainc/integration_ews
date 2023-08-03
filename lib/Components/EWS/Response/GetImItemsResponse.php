<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetImItemsResponse.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a GetImItems request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetImItemsResponse extends ResponseMessageType
{
    /**
     * Contains a list of instant messaging groups and instant messaging
     * contacts.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ImItemListType
     */
    public $ImItemList;
}
