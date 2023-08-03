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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines an array of resolutions for an ambiguous name.
 *
 * @package OCA\EWS\Components\EWS\Array
 *
 * @todo Implement FindResponsePagingAttributes trait.
 */
class ArrayOfResolutionType extends ArrayType
{
    /**
     * Represents the next denominator to use for the next request when you are
     * using fraction page views.
     *
     * @since Exchange 2007
     *
     * @var integer[]
     */
    public $AbsoluteDenominator = array();

    /**
     * This attribute will be true if the current results contain the last item
     * in the query, so that additional paging is not needed.
     *
     * @since Exchange 2007
     *
     * @var boolean[]
     */
    public $IncludesLastItemInRange = array();

    /**
     * Represents the next index that should be used for the next request when
     * you are using an indexed page view.
     *
     * @since Exchange 2007
     *
     * @var integer[]
     */
    public $IndexedPagingOffset = array();

    /**
     * Represents the new numerator value to use for the next request when you
     * are using fraction page views.
     *
     * @since Exchange 2007
     *
     * @var integer[]
     */
    public $NumeratorOffset = array();

    /**
     * Contains a single resolved entity.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ResolutionType[]
     */
    public $Resolution = array();

    /**
     * Represents the total number of items in the view.
     *
     * @since Exchange 2007
     *
     * @var integer[]
     */
    public $TotalItemsInView = array();
}
