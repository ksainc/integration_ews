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

class SettingsObject {

	public string $UserId;					// nextcloud user id
	public DateTimeZone $UserTimeZone; 		// nextcloud user timezone
	public string $ContactsPrevalence;		// contacts sync prevalence
	public string $ContactsFrequency;		// contacts sync frequency
	public string $EventsPrevalence;		// eventss sync prevalence
	public string $EventsFrequency;			// events sync frequency


	public function __construct (array $parameters) {
		if (is_array($parameters)) {
			$this->setAttributes($parameters);
		}
	}

	/**
	 * Converts all attributes to key/value paired array
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return array of key/value pairs, of attributes
	 */
	public function getAttributes(): array {

		$parameters = [];
		$parameters['user_id'] = $this->UserId;
		if ($this->UserTimeZone instanceof DateTimeZone) {
			$parameters['user_timezone'] = $this->UserTimeZone->getName();
		}
		$parameters['contacts_prevalence'] = $this->ContactsPrevalence;
		$parameters['contacts_frequency'] = $this->ContactsFrequency;
		$parameters['events_prevalence'] = $this->EventsPrevalence;
		$parameters['events_frequency'] = $this->EventsFrequency;
		
	}

	/**
	 * Converts key/value paired attribute array to object attributes
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $parameters	collection of key/value paired attributes
	 * 
	 * @return void
	 */
	public function setAttributes(array $parameters): void {

		foreach ($parameters as $key => $value) {
			switch ($key) {
				case 'user_id':
					$this->UserId = $value;
					break;
				case 'user_timezone':
					$tz = @timezone_open($value);
					if ($tz instanceof DateTimeZone) {
						$this->UserTimeZone = $tz;
					}
					unset($tz);
					break;
				case 'contacts_prevalence':
					$this->ContactsPrevalence = $value;
					break;
				case 'contacts_frequency':
					$this->ContactsFrequency = $value;
					break;
				case 'events_prevalence':
					$this->EventsPrevalence = $value;
					break;
				case 'events_frequency':
					$this->EventsFrequency = $value;
					break;
			}
		}

	}

}
