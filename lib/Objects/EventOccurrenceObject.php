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

namespace OCA\EWS\Objects;

use DateTime;

class EventOccurrenceObject {
    public ?string $Pattern = null;         // Pattern - A - Absolute / R - Relative
	public ?string $Precision = null;       // Time Scale - D - Daily / W - Weekly / M - Monthly / Y - Yearly
    public ?string $Interval = null;        // Time Interval - Every 2 Days / Every 4 Weeks / Every 1 Year
    public ?string $Iterations = null;      // Number of recurrence
    public ?DateTime $Concludes = null;     // Date to stop recurrence
    public array $Excludes = [];
    public array $OnDayOfWeek = [];
    public array $OnDayOfMonth = [];
    public array $OnDayOfYear = [];
    public array $OnWeekOfMonth = [];
    public array $OnWeekOfYear = [];
    public array $OnMonthOfYear = [];

    public function __construct() {}
}
