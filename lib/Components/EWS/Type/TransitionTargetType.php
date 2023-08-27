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
 * Specifies the target of the time zone transition.
 *
 * The target is either a time zone period or a group of time zone transitions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TransitionTargetType extends StringType
{
    /*Constructor method with arguments*/
    public function __construct(string $Kind = null, string $Value = null)
    {
        $this->Kind = $Kind;
        $this->_ = $Value;
    }
    /**
     * Indicates whether the time zone transition target is a time zone period
     * or of a group of time zone transitions.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\TransitionTargetKindType
     */
    public $Kind;
}
