<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
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
 * Describes the start date, due date, completed date and flag status for a task item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FlagType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $FlagStatus = null, string $StartDate = null, string $DueDate = null, string $CompleteDate = null)
    {
        $this->FlagStatus = $FlagStatus;
        $this->StartDate = $StartDate;
        $this->DueDate = $DueDate;
        $this->CompleteDate = $CompleteDate;
    }
    /**
     * The FlagStatus contains the aggregated flag status for conversation items in the current folder.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     * 
     * @see \OCA\EWS\Components\EWS\Enumeration\FlagStatusType
     */
    public $FlagStatus;
    /**
     * Represents represents the start date of a task.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a date object that extends DateTime.
     */
    public $StartDate;
    /**
     * Represents represents the due date of a task.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a date object that extends DateTime.
     */
    public $DueDate;
    /**
     * Represents represents the complete date of a task.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a date object that extends DateTime.
     */
    public $CompleteDate;
}
