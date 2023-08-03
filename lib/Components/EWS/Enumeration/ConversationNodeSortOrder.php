<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ConversationNodeSortOrder.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the sort order used for the result of a GetConversationItems request.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConversationNodeSortOrder extends Enumeration
{
    /**
     * Order the conversations by their date in ascending order.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DATE_ORDER_ASC = 'DateOrderAscending';

    /**
     * Order the conversations by their date in descending order.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DATE_ORDER_DESC = 'DateOrderDescending';

    /**
     * Returns the conversations according to the tree in ascending order.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TREE_ORDER_ASC = 'TreeOrderAscending';

    /**
     * Returns the conversations according to the tree in descending order.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TREE_ORDER_DESC = 'TreeOrderDescending';
}
