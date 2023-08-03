<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindConversationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a FindConversation Operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FindConversationResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of conversations.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfConversationsType
     */
    public $Conversations;
}
