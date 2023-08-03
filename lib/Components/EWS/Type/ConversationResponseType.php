<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ConversationResponseType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a single conversation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConversationResponseType extends Type
{
    /**
     * Indicates whether the conversation can be deleted.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $CanDelete;

    /**
     * Contains the identifier of the conversation.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ConversationId;

    /**
     * Specifies a collection of conversation nodes.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfConversationNodesType
     */
    public $ConversationNodes;

    /**
     * Specifies the synchronization state of a conversation.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $SyncState;
}
