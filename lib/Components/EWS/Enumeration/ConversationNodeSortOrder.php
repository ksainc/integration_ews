<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
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
