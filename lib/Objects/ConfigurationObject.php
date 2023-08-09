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
use DateTimeZone;

class ConfigurationObject {

	public ?DateTimeZone $SystemTimeZone = null;
	public string $UserId = '';						// nextcloud user id
	public ?DateTimeZone $UserTimeZone = null; 		// nextcloud user timezone
	public int $ContactsHarmonize = -1;			// contacts harmonize
	public string $ContactsPrevalence = '';		// contacts prevalence
	public string $ContactsPresentation = '';
	public int $EventsHarmonize = -1;			// events harmonize
	public string $EventsPrevalence = '';		// events prevalence
	public ?DateTimeZone $EventsTimezone = null;
	public string $AccountProvider = '';
	public string $AccountId = '';
	public string $AccountProtocol = '';
	public string $AccountConnected = '';
	
}
