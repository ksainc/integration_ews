<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetConversationItemsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response message for a GetConversationItems request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetConversationItemsResponseMessageType extends ResponseMessageType
{
    /**
     * Represents a single conversation.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ConversationResponseType
     */
    public $Conversation;
}
