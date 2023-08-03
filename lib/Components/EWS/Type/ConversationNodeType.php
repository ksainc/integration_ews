<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ConversationNodeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a node in a conversation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConversationNodeType extends Type
{
    /**
     * Represents the Internet message identifier of an item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $InternetMessageId;

    /**
     * Specifies all the items in the conversation node.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAllItemsType
     */
    public $Items;

    /**
     * Specifies the identifier of the parent Internet message.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ParentInternetMessageId;
}
