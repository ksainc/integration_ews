<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ModifiedEventType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents an event in which an item or folder is modified.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ModifiedEventType extends BaseObjectChangedEventType
{
    /**
     * Represents the count of unread items within a given folder.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $UnreadCount;
}
