<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PostReplyItemType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a reply to a post item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PostReplyItemType extends PostReplyItemBaseType
{
    /**
     * Represents the new body content of a post item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BodyType
     */
    public $NewBodyContent;
}
