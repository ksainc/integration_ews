<?php
declare(strict_types=1);

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

namespace OCA\EWS\Utile;

class Misc {

    // generate iana time zone file
    function generateIANA() {
        // read iana time zones
		$file = fopen("/var/www/nextcloud/data/iana_time_zones.csv","r");

		$zones = array();
		$zoneid = null;

		while(! feof($file))
		{
			$data = fgetcsv($file);

			if ($zoneid !== $data[0]) {
				$zoneid = $data[0];
				$zones[] = array('id' => $zoneid);
				$zonelc = array_key_last($zones);
			}
			if ($zoneid === $data[0] && date('Y', $data[3]) <= '2023') {
				if ($data[5] == '1') {
					$zones[$zonelc]['dlabbr'] = $data[2];
					$zones[$zonelc]['dloff'] = $data[4];
					$zones[$zonelc]['dltrn'] = date(DATE_ISO8601, $data[3]);
				} elseif ($data[5] == '0') {
					$zones[$zonelc]['stabbr'] = $data[2];
					$zones[$zonelc]['stoff'] = $data[4];
					$zones[$zonelc]['sttrn'] = date(DATE_ISO8601, $data[3]);
				}
			}
		}

		fclose($file);

		$file = fopen("/var/www/nextcloud/data/IANA-Timezones.csv", "w") or die("Unable to open file!");

		fwrite($file, 
			"'id', " .
			"'stabbr', " .
			"'stoff', " .
			"'sttrn_date', " .
			"'sttrn_month', " .
			"'sttrn_day', " .
			"'sttrn_hour', " .
			"'sttrn_minute', " .
			"'sttrn_wom', " .
			"'sttrn_dow', " .
			"'dlabbr', " .
			"'dloff', " .
			"'dltrn_date', " .
			"'dltrn_month', " .
			"'dltrn_day', " .
			"'dltrn_hour', " .
			"'dltrn_minute', " .
			"'dltrn_wom', " .
			"'dltrn_dow', " .
			"\n"
		);

		foreach ($zones as $zonelc => $zonedata) {
			try {
				$dt = new DateTime('2023-1-1', new DateTimeZone($zonedata['id']));
			} catch (\Throwable $th) {
				continue;
			}
			$tz = $dt->getTimezone();
			$tztrns = $tz->getTransitions(strtotime('2023-1-1'), strtotime('2023-12-31'));

			if (count($tztrns) == 1) {
				$zones[$zonelc]['stoff'] = $dt->format('P');;
				$zones[$zonelc]['sttrn'] = null;
				$zones[$zonelc]['dlabbr'] = null;
				$zones[$zonelc]['dloff'] = null;
				$zones[$zonelc]['dltrn'] = null;
			}
			else {
				foreach ($tztrns as $entry) {
					if ($entry['time'] == $dt->format("Y-m-d\\TH:i:sO")) {
						$zones[$zonelc]['sttrn'] = null;
						$zones[$zonelc]['stoff'] = $entry['offset'];
					}
					elseif ($entry['isdst'] == true) {
						$zones[$zonelc]['dltrn'] = $entry['time'];
						$dttran = new DateTime($entry['time']);
						$dttran->setTimezone(new DateTimeZone($zonedata['id']));
						$zones[$zonelc]['dloff'] = $dttran->format('P');
						$zones[$zonelc]['dltrn_month'] = $dttran->format('n');
						$zones[$zonelc]['dltrn_day'] = $dttran->format('j');
						$zones[$zonelc]['dltrn_hour'] = $dttran->format('g');
						$zones[$zonelc]['dltrn_minute'] = $dttran->format('i');
						$zones[$zonelc]['dltrn_dow'] = $dttran->format('N');
						$zones[$zonelc]['dltrn_wom'] = $this->weekOfMonth(strtotime($entry['time']));
					}
					elseif ($entry['isdst'] == false) {
						$zones[$zonelc]['sttrn'] = $entry['time'];
						$dttran = new DateTime($entry['time']);
						$dttran->setTimezone(new DateTimeZone($zonedata['id']));
						$zones[$zonelc]['stoff'] = $dttran->format('P');
						$zones[$zonelc]['sttrn_month'] = $dttran->format('n');
						$zones[$zonelc]['sttrn_day'] = $dttran->format('j');
						$zones[$zonelc]['sttrn_hour'] = $dttran->format('g');
						$zones[$zonelc]['sttrn_minute'] = $dttran->format('i');
						$zones[$zonelc]['sttrn_dow'] = $dttran->format('N');
						$zones[$zonelc]['sttrn_wom'] = $this->weekOfMonth(strtotime($entry['time']));
					}
				}
			}

			fwrite($file, 
				"'" . $zones[$zonelc]['id'] . "', " .
				"'" . $zones[$zonelc]['stabbr'] . "', " .
				"'" . $zones[$zonelc]['stoff'] . "', " .
				"'" . $zones[$zonelc]['sttrn'] . "', " .
				"'" . $zones[$zonelc]['sttrn_month'] . "', " .
				"'" . $zones[$zonelc]['sttrn_day'] . "', " .
				"'" . $zones[$zonelc]['sttrn_hour'] . "', " .
				"'" . $zones[$zonelc]['sttrn_minute'] . "', " .
				"'" . $zones[$zonelc]['sttrn_wom'] . "', " .
				"'" . $zones[$zonelc]['sttrn_dow'] . "', " .
				"'" . $zones[$zonelc]['dlabbr'] . "', " .
				"'" . $zones[$zonelc]['dloff'] . "', " .
				"'" . $zones[$zonelc]['dltrn'] . "', " .
				"'" . $zones[$zonelc]['dltrn_month'] . "', " .
				"'" . $zones[$zonelc]['dltrn_day'] . "', " .
				"'" . $zones[$zonelc]['dltrn_hour'] . "', " .
				"'" . $zones[$zonelc]['dltrn_minute'] . "', " .
				"'" . $zones[$zonelc]['dltrn_wom'] . "', " .
				"'" . $zones[$zonelc]['dltrn_dow'] . "', " .
				"\n"
			);

			// Write
			/*
			fwrite($file, 
				"id => '" . $zones[$zonelc]['id'] . "', " .
				"stabbr => '" . $zones[$zonelc]['stabbr'] . "', " .
				"stoff => '" . $zones[$zonelc]['stoff'] . "', " .
				"sttrn => '" . $zones[$zonelc]['sttrn'] . "', " .
				"sttrn_month => '" . $zones[$zonelc]['sttrn_month'] . "', " .
				"sttrn_day => '" . $zones[$zonelc]['sttrn_day'] . "', " .
				"sttrn_hour => '" . $zones[$zonelc]['sttrn_hour'] . "', " .
				"sttrn_minute => '" . $zones[$zonelc]['sttrn_minute'] . "', " .
				"sttrn_dow => '" . $zones[$zonelc]['sttrn_dow'] . "', " .
				"dlabbr => '" . $zones[$zonelc]['dlabbr'] . "', " .
				"dloff => '" . $zones[$zonelc]['dloff'] . "', " .
				"dltrn => '" . $zones[$zonelc]['dltrn'] . "', " .
				"dltrn_month => '" . $zones[$zonelc]['dltrn_month'] . "', " .
				"dltrn_day => '" . $zones[$zonelc]['dltrn_day'] . "', " .
				"dltrn_hour => '" . $zones[$zonelc]['dltrn_hour'] . "', " .
				"dltrn_minute => '" . $zones[$zonelc]['dltrn_minute'] . "', " .
				"dltrn_dow => '" . $zones[$zonelc]['dltrn_dow'] . "', " .
				"\n"
			);
			*/
		}
		fclose($file);
    }

    function generateEWS($RemoteStore) {

		$RemoteCommonService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteCommonService::class);

		$zones = $RemoteCommonService->fetchTimeZone($RemoteStore);

		$data = [];

		foreach ($zones->TimeZoneDefinition as $zone) {
			
			$data[] = [
				'Id' => $zone->Id, // Id
				'Name' => $zone->Name, // Name
				'Periods' => json_encode($zone->Periods), // Periods
				'Transitions' => json_encode($zone->Transitions), // Transitions
				'TransitionsGroups' => json_encode($zone->TransitionsGroups) // TransitionsGroups
			];
		}

		array_multisort(array_column($data, 'Id'), SORT_ASC, $data);

		$file = fopen("/var/www/nextcloud/data/EWS-Timezones.txt", "w") or die("Unable to open file!");
		
		fwrite($file, "[\n");
		foreach ($data as $entry) {
			fwrite($file, "[\n" .
				"\t'Id' => '" . $entry['Id'] . "',\n" .
				"\t'Name' => '" . $entry['Name'] . "',\n" .
				"\t'Periods' => '" . $entry['Periods'] . "',\n" .
				"\t'Transitions' => '" . $entry['Transitions'] . "',\n" .
				"\t'TransitionsGroups' => '" . $entry['TransitionsGroups'] . "',\n" .
			"],\n");
		}
		fwrite($file, "]\n");
		fclose($file);
    }
}
