<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\ApplyConversationActionType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to apply actions to items in a conversation.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ApplyConversationActionType extends BaseRequestType
{
    /**
     * Contains a collection of conversations and the actions to apply to them.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfApplyConversationActionType
     */
    public $ConversationActions;
}
