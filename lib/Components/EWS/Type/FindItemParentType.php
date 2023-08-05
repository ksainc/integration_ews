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

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the results of a search of a single root folder during a FindItem
 * operation.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a FindResponsePagingAttributes trait.
 */
class FindItemParentType extends Type
{
    /**
     * Represents the next denominator to use for the next request when you are
     * using fraction page views.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $AbsoluteDenominator;

    /**
     * Contains a collection of groups found that have the search and
     * aggregation criteria identified in the FindItem operation request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfGroupedItemsType
     */
    public $Groups;

    /**
     * Indicates whether the current results contain the last item in the query
     * so that additional paging is not needed.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IncludesLastItemInRange;

    /**
     * Represents the next index that should be used for the next request when
     * you are using an indexed page view.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $IndexedPagingOffset;

    /**
     * Contains an array of items found that have the search criteria identified
     * in the FindItem operation request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRealItemsType
     */
    public $Items;

    /**
     * Represents the new numerator value to use for the next request when you
     * are using fraction page views.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $NumeratorOffset;

    /**
     * Represents the total number of items in the view.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $TotalItemsInView;
}
