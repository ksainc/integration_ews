<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\StandardGroupByType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the standard grouping and aggregating mechanisms for a grouped
 * FindItem operation.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class StandardGroupByType extends Enumeration
{
    /**
     * Groups by message:ConversationTopic and aggregates on
     * item:DateTimeReceived (maximum).
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONVERSATION_TOPIC = 'ConversationTopic';
}
