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
 * Describes where the paged view starts and the maximum number of folders
 * returned in a FindFolder request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FractionalPageViewType extends BasePagingType
{
    /*Constructor method with arguments*/
    public function __construct(int $denominator = 0, int $numerator = 0, int $limit = 512)
    {
        $this->Denominator = $denominator;
        $this->Numerator = $numerator;
        $this->MaxEntriesReturned = $limit;
    }

    /**
     * Represents the denominator of the fractional offset from the start of the
     * total number of folders in the result set.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Denominator;

    /**
     * Represents the numerator of the fractional offset from the start of the
     * result set.
     *
     * The numerator must be equal to or less than the denominator.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Numerator;
}
