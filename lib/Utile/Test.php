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

class Test {

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

    function generateEWS() {
        // retrieve EWS connection information
		$server = $this->config->getUserValue($uid, Application::APP_ID, 'server');
		$account_id = $this->config->getUserValue($uid, Application::APP_ID, 'account_id');
		$account_secret = $this->config->getUserValue($uid, Application::APP_ID, 'account_secret');
		$contacts_prevalence = $this->config->getUserValue($uid, Application::APP_ID, 'contacts_prevalence');
		$events_prevalence = $this->config->getUserValue($uid, Application::APP_ID, 'events_prevalence');

		// create EWS client.
		$RemoteStore = new EWSClient($server, $account_id, $account_secret, EWSClient::VERSION_2010_SP2);
		
		$zones = $this->RemoteCommonService->fetchTimeZone($RemoteStore);

		$file = fopen("/var/www/nextcloud/data/EWS-Timezones.txt", "w") or die("Unable to open file!");
		foreach ($zones->TimeZoneDefinition as $zone) {
			
			$zonedata = array();

			foreach ($zone->Periods->Period as $entry) {

				$p = array();
				//if (str_contains($period->Id, '/Daylight')) { continue; }

				$p['id'] = str_replace("trule:Microsoft/Registry/", "", $entry->Id);
				$p['name'] = $entry->Name;
				$p['bias'] = $entry->Bias;

				$offset = str_replace("PT", "", $entry->Bias);
				$offset = str_replace("H0", "H00", $offset); // Convert Hours
				$offset = str_replace("H", "", $offset); // Remove Hour Indicator
				$offset = str_replace("M", "", $offset); // Remote Minutes Indicator
				$offset = str_replace("0S", "", $offset); // Remove Seconds
				$offset = intval($offset); // convert to integer
				$offset = $offset * -1; // convert to standard offset

				if ($offset < 0) { 
					$dt = new DateTime('now', new DateTimeZone($offset));
				} else {
					$dt = new DateTime('now', new DateTimeZone('+' . $offset));
				}
				$p['offset'] = $dt->format('P');

				if ($p['name'] == 'Standard') {
					$p['id'] = str_replace("/Standard", "", $p['id']);
					$zonedata['S'] = $p;
				} elseif ($p['name'] == 'Daylight') {
					$p['id'] = str_replace("/Daylight", "", $p['id']);
					$zonedata['D'] = $p;
				} else {
					throw new Exception('Invalid Period');
				}
			}

			foreach ($zone->TransitionsGroups->TransitionsGroup as $tg) {
				foreach ($tg->RecurringDayTransition as $ts) {

					if (str_contains($ts->To->_, '/Standard')) {
						$p = 'D';
					} elseif (str_contains($ts->To->_, '/Daylight')) {
						$p = 'S';
					} else {
						throw new Exception('Invalid Transition');
					}

					$zonedata[$p]['to'] = str_replace("trule:Microsoft/Registry/", "", $ts->To->_);
					$zonedata[$p]['month'] = $ts->Month;
					$zonedata[$p]['dow'] = $ts->DayOfWeek;
					$zonedata[$p]['wom'] = $ts->Occurrence;
					$zonedata[$p]['time'] = $ts->TimeOffset;
	
				}
			}

			$zoneid = str_replace("/Standard", "", $zonedata['S']['id']);
			
			$data .= "array( id => '" . $zonedata['S']['id'] . // Id
			"', sname => '" . $zonedata['S']['name'] . // Name
			"', sbias => '" . $zonedata['S']['bias'] . // Bias
			"', soffset => '" . $zonedata['S']['offset'] . // Offset
			"', smonth => '" . $zonedata['S']['month'] . // Month
			"', sdow => '" . $zonedata['S']['dow'] . // Day Of Week
			"', swom => '" . $zonedata['S']['wom'] . // Week Of Month
			"', stime => '" . $zonedata['S']['time'] . // Time
			//"', sto => '" . $zonedata['S']['to'] . // To Period
			//"', did => '" . $zonedata['D']['id'] . // Id
			"', dname => '" . $zonedata['D']['name'] . // Name
			"', dbias => '" . $zonedata['D']['bias'] . // Bias
			"', doffset => '" . $zonedata['D']['offset'] . // Offset
			"', dmonth => '" . $zonedata['D']['month'] . // Month
			"', ddow => '" . $zonedata['D']['dow'] . // Day Of Week
			"', dwom => '" . $zonedata['D']['wom'] . // Week Of Month
			"', dtime => '" . $zonedata['D']['time'] . // Time
			//"', dto => '" . $zonedata['D']['to'] . // To Period
			"' ), \n";
		}

		fwrite(
			$file,
			"array(\n" . $data . ");"
		);

		fclose($file);
    }
}
