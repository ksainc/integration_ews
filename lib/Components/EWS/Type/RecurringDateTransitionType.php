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

/**
 * Represents a time zone transition that occurs on a specific date each year.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RecurringDateTransitionType extends RecurringTimeTransitionType
{
    /*Constructor method with arguments*/
    public function __construct($To = null, string $Offset = null, int $Month = null, int $Day = null)
    {
        $this->To = $To;
        $this->TimeOffset = $Offset;
        $this->Month = $Month;
        $this->Day = $Day;

    }
    /**
     * The day of the month on which the time zone transition occurs.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $Day;
}
