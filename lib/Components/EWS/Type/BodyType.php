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
 * Specifies the body of an item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BodyType extends StringType
{
    /*Constructor method with arguments*/
    public function __construct(string $Type, string $Contents)
    {
        $this->BodyType = $Type;
        $this->_ = $Contents;
    }

    /**
     * Specifies the type of the body.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeType
     */
    public $BodyType;

    /**
     * Boolean value that indicates whether the body is truncated.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsTruncated;
}
