<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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

/**
 * Describes how paged conversation or item information is returned for a
 * FindItem operation or FindConversation operation request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class IndexedPageViewType extends BasePagingType
{
    /*Constructor method with arguments*/
    public function __construct(string $base = 'Beginning', int $offset = 0, int $limit = 512)
    {
        $this->BasePoint = $base;
        $this->Offset = $offset;
        $this->MaxEntriesReturned = $limit;
    }

    /**
     * Describes whether the page of items or conversations will start from the
     * beginning or the end of the set of items or conversations that are found
     * by using the search criteria.
     *
     * Seeking from the end always searches backward.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\IndexBasePointType
     */
    public $BasePoint;

    /**
     * Describes the offset from the BasePoint.
     *
     * If BasePoint is equal to Beginning, the offset is positive. If BasePoint
     * is equal to End, the offset is handled as if it were negative. This
     * identifies which item or conversation will be the first to be delivered
     * in the response.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Offset;
}
