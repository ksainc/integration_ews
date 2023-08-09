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

namespace OCA\EWS\Service;

use Exception;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

use OCA\EWS\AppInfo\Application;

class ConfigurationService {

	/**
	 * Default System Configuration 
	 * @var array
	 * */
	private const _SYSTEM = [
		'harmonization_mode' => 'P',
		'harmonization_thread_duration' => '3600',
		'harmonization_thread_pause' => '15',
	];

	/**
	 * Default User Configuration 
	 * @var array
	 * */
	private const _USER = [
		'account_provider' => '',
		'account_id' => '',
		'account_secret' => '',
		'account_protocol' => 'Exchange2007',
		'account_connected' => '0',
		'account_harmonization_state' => '0',
		'account_harmonization_start' => '0',
		'account_harmonization_end' => '0',
		'account_harmonization_tid' => '0',
		'account_harmonization_thb' => '0',
		'contacts_harmonize' => '5',
		'contacts_prevalence' => 'R',
		'contacts_presentation' => 'DisplayName',
		'events_harmonize' => '5',
		'events_prevalence' => 'R',
		'events_timezone' => '',
	];

	/** @var LoggerInterface */
	private $_logger;

	/** @var IConfig */
	private $_ds;

	public function __construct(LoggerInterface $logger, IConfig $config)
	{
		$this->logger = $logger;
		$this->_ds = $config;
	}

	/**
	 * Retrieves all user authentication parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of key/value pairs, of parameters
	 */
	public function retrieveAuthentication($uid): array {
		
		// define defaults authentication parameters
		$keys = ['account_provider', 'account_id', 'account_secret', 'account_protocol'];
		// retrieve all user authentication parameters
		$parameters = [];
		foreach ($keys as $key) {
			$parameters[$key] = $this->retrieveUserValue($uid, $key);
		}
		// return configuration parameters
		return $parameters;

	}

	/**
	 * Retrieves collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param array $keys		collection of configuration parameter keys
	 * 
	 * @return array of key/value pairs, of configuration parameter
	 */
	public function retrieveUser(string $uid, ?array $keys = null): array {

		// define parameters place holder
		$parameters = [];
		// evaluate if we are looking for specific parameters
		if (!isset($keys) || count($keys) == 0) {
			$keys = array_keys(self::_USER);
			// retrieve other user preferences
			$parameters['system_timezone'] = $this->_ds->getUserValue($uid, 'core', 'timezone');
			$parameters['user_id'] = $uid;
			$parameters['user_timezone'] = $this->_ds->getUserValue($uid, 'calendar', 'timezone');
		}
		// retrieve system configuration values
		foreach ($keys as $entry) {
			$parameters[$entry] = $this->retrieveUserValue($uid, $entry);
		}
		// remove account secret
		if (isset($parameters['account_secret'])) {
			$parameters['account_secret'] = null;
		}
		// return configuration parameters
		return $parameters;

	}

	/**
	 * Retrieves single system configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param string $key		configuration parameter key
	 * 
	 * @return string configuration parameter value
	 */
	public function retrieveUserValue(string $uid, string $key): string {

		// retrieve configured parameter value
		$value = $this->_ds->getUserValue($uid, Application::APP_ID, $key);
		// evaluate if value was returned
		if (!empty($value)) {
			// return configuration parameter value
			return $value;
		} else {
			// return default system configuration value
			return self::_USER[$key];
		}

	}

	/**
	 * Deposit collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid			nextcloud user id
	 * @param array $parameters		collection of key/value pairs, of parameters
	 * 
	 * @return void
	 */
	public function depositUser($uid, array $parameters): void {
		
		// deposit system configuration parameters
		foreach ($parameters as $key => $value) {
			$this->depositUserValue($uid, $key, $value);
		}

	}

	/**
	 * Deposit single system configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param string $key		configuration parameter key
	 * @param string $value		configuration parameter value
	 * 
	 * @return void
	 */
	public function depositUserValue(string $uid, string $key, string $value): void {
		
		// deposit user configuration parameter value
		$this->_ds->setUserValue($uid, Application::APP_ID, $key, $value);

	}

	/**
	 * Destroy collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param array $keys		collection of configuration parameter keys
	 * 
	 * @return void
	 */
	public function destroyUser(string $uid, ?array $keys = null): array {

		// evaluate if we are looking for specific parameters
		if (!isset($keys) || count($keys) == 0) {
			$keys = $this->_ds->getUserKeys($uid, Application::APP_ID);
		}
		// destroy system configuration parameter
		foreach ($keys as $entry) {
			$this->destroyUserValue($uid, $entry);
		}

	}

	/**
	 * Destroy single user configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param string $key		configuration parameter keys
	 * 
	 * @return void
	 */
	public function destroyUserValue(string $uid, string $key): void {

		// destroy user configuration parameter
		$this->_ds->deleteUserValue($uid, Application::APP_ID, $key);

	}

	/**
	 * Retrieves collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param array $keys	collection of configuration parameter keys
	 * 
	 * @return array of key/value pairs, of configuration parameter
	 */
	public function retrieveSystem(?array $keys = null): array {

		// evaluate if we are looking for specific parameters
		if (!isset($keys) || count($keys) == 0) {
			$keys = array_keys(self::_SYSTEM);
		}
		// retrieve system configuration values
		$parameters = [];
		foreach ($keys as $entry) {
			$parameters[$entry] = $this->retrieveSystemValue($entry);
		}
		// return configuration parameters
		return $parameters;

	}

	/**
	 * Retrieves single system configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $key	configuration parameter key
	 * 
	 * @return string configuration parameter value
	 */
	public function retrieveSystemValue(string $key): string {

		// retrieve configured parameter value
		$value = $this->_ds->getAppValue(Application::APP_ID, $key);
		// evaluate if value was returned
		if (!empty($value)) {
			// return configuration parameter value
			return $value;
		} else {
			// return default system configuration value
			return self::_SYSTEM[$key];
		}

	}

	/**
	 * Deposit collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param array $parameters	collection of key/value pairs, of parameters
	 * 
	 * @return void
	 */
	public function depositSystem(array $parameters): void {
		
		// deposit system configuration parameters
		foreach ($parameters as $key => $value) {
			$this->depositSystemValue($key, $value);
		}

	}

	/**
	 * Deposit single system configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $key		configuration parameter key
	 * @param string $value		configuration parameter value
	 * 
	 * @return void
	 */
	public function depositSystemValue(string $key, string $value): void {
		
		// deposit system configuration parameter value
		$this->_ds->setAppValue(Application::APP_ID, $key, $value);

	}

	/**
	 * Destroy collection of system configuration parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param array $keys	collection of configuration parameter keys
	 * 
	 * @return void
	 */
	public function destroySystem(?array $keys = null): array {

		// evaluate if we are looking for specific parameters
		if (!isset($keys) || count($keys) == 0) {
			$keys = $this->_ds->getAppKeys(Application::APP_ID);
		}
		// destroy system configuration parameter
		foreach ($keys as $entry) {
			$this->destroySystemValue($entry);
		}

	}

	/**
	 * Destroy single system configuration parameter
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return void
	 */
	public function destroySystemValue(string $key): void {

		// destroy system configuration parameter
		$this->_ds->deleteAppValue(Application::APP_ID, $key);

	}

	/**
	 * Converts key/value paired attribute array to object properties
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $parameters	collection of key/value paired attributes
	 * 
	 * @return ConfigurationObject
	 */
	public function toUserConfigurationObject(array $parameters): \OCA\EWS\Objects\ConfigurationObject {

		// construct configuration object
		$o = new \OCA\EWS\Objects\ConfigurationObject();

		foreach ($parameters as $key => $value) {
			switch ($key) {
				case 'system_timezone':
					if (!empty($value)) {
						$tz = @timezone_open($value);
						if ($tz instanceof \DateTimeZone) {
							$o->SystemTimeZone = $tz;
						}
					}
					unset($tz);
					break;
				case 'user_id':
					$o->UserId = $value;
					break;
				case 'user_timezone':
					if (!empty($value)) {
						$tz = @timezone_open($value);
						if ($tz instanceof \DateTimeZone) {
							$o->UserTimeZone = $tz;
						}
					}
					unset($tz);
					break;
				case 'contacts_harmonize':
					$o->ContactsHarmonize = $value;
					break;
				case 'contacts_prevalence':
					$o->ContactsPrevalence = $value;
					break;
				case 'contacts_presentation':
					$o->ContactsPresentation = $value;
					break;
				case 'events_harmonize':
					$o->EventsHarmonize = $value;
					break;
				case 'events_prevalence':
					$o->EventsPrevalence = $value;
					break;
				case 'events_timezone':
					if (!empty($value)) {
						$tz = @timezone_open($value);
						if ($tz instanceof \DateTimeZone) {
							$o->EventsTimezone = $tz;
						}
					}
					unset($tz);
					break;
				case 'account_provider':
					$o->AccountProvider = $value;
					break;
				case 'account_id':
					$o->AccountId = $value;
					break;
				case 'account_protocol':
					$o->AccountProtocol = $value;
					break;
				case 'account_connected':
					$o->AccountConnected = $value;
					break;
			}
		}
		// return configuration object
		return $o;

	}

	/**
	 * Gets harmonization mode
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization mode (default P - passive)
	 */
	public function getHarmonizationMode(): string {

		// retrieve harmonization mode
		$mode = $this->retrieveSystemValue('harmonization_mode');
		// return harmonization mode or default
		if (!empty($mode)) {
			return $mode;
		}
		else {
			return self::_SYSTEM['harmonization_mode'];
		}

	}

	/**
	 * Sets harmonization mode
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $mode		harmonization mode (A - Active / P - Passive)
	 * 
	 * @return void
	 */
	public function setHarmonizationMode(string $mode): void {
		
		// set harmonization mode
		$this->depositSystemValue('harmonization_mode', $mode);

	}

	/**
	 * Gets harmonization status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return object
	 */
	public function getHarmonizationStatus(string $uid): object {

		// construct status object
		$hs = (object) ['State' => null, 'Started' => null, 'Ended' => null];
		// retrieve status values
		$hs->State = (int) $this->retrieveUserValue($uid, 'account_harmonization_state');
		$hs->Started = (int) $this->retrieveUserValue($uid, 'account_harmonization_start');
		$hs->Ended = (int) $this->retrieveUserValue($uid, 'account_harmonization_end');
		// return status object
		return $hs;

	}

	/**
	 * Sets harmonization status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param int $state		harmonization state (1 - running, 0 - not running)
	 * @param int $start		harmonization start epoch time stamp
	 * @param int $end			harmonization end epoch time stamp
	 * 
	 * @return void
	 */
	public function setHarmonizationStatus(string $uid, int $state, ?int $start, ?int $end): void {
		
		// update harmonization values
		$this->depositUserValue($uid, 'account_harmonization_state', $state);
		if (isset($start)) {
			$this->depositUserValue($uid, 'account_harmonization_start', $start);
		}
		if (isset($end)) {
			$this->depositUserValue($uid, 'account_harmonization_end', $end);
		}

	}

	/**
	 * Gets harmonization thread run duration interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization thread run duration interval (default 3600 seconds)
	 */
	public function getHarmonizationThreadDuration(): int {

		// retrieve value
		$interval = $this->retrieveSystemValue('harmonization_thread_duration');
		
		// return value or default
		if (is_numeric($interval)) {
			return intval($interval);
		}
		else {
			return intval($self::_SYSTEM['harmonization_thread_duration']);
		}

	}

	/**
	 * Sets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $interval		harmonization thread pause interval in seconds
	 * 
	 * @return void
	 */
	public function setHarmonizationThreadDuration(int $interval): void {
		
		// set value
		$this->depositSystemValue('harmonization_thread_duration', $interval);

	}

	/**
	 * Gets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string harmonization thread pause interval (default 5 seconds)
	 */
	public function getHarmonizationThreadPause(): int {

		// retrieve value
		$interval = $this->retrieveSystemValue('harmonization_thread_pause');
		
		// return value or default
		if (is_numeric($interval)) {
			return intval($interval);
		}
		else {
			return intval($self::_SYSTEM['harmonization_thread_pause']);
		}

	}

	/**
	 * Sets harmonization thread pause interval
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $interval		harmonization thread pause interval in seconds
	 * 
	 * @return void
	 */
	public function setHarmonizationThreadPause(int $interval): void {
		
		// set value
		$this->depositSystemValue('harmonization_thread_pause', $interval);

	}

	/**
	 * Gets harmonization thread id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return string|null thread id if exists | null if does not exist
	 */
	public function getHarmonizationThreadId(string $uid): int {

		// retrieve thread id
		$tid = $this->retrieveUserValue($uid, 'account_harmonization_tid');
		// return thread id
		if (is_numeric($tid)) {
			return intval($tid);
		}
		else {
			return 0;
		}

	}

	/**
	 * Sets harmonization thread id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param string $tid		thread id
	 * 
	 * @return void
	 */
	public function setHarmonizationThreadId(string $uid, int $tid): void {
		
		// update harmonization thread id
		$this->depositUserValue($uid, 'account_harmonization_tid', (string) $tid);

	}

	/**
	 * Gets harmonization thread heart beat
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return int thread heart beat time stamp if exists | null if does not exist
	 */
	public function getHarmonizationThreadHeartBeat(string $uid): int {

		// retrieve thread heart beat
		$thb = $this->retrieveUserValue($uid, 'account_harmonization_thb');
		// return thread heart beat
		if (is_numeric($thb)) {
			return (int) $thb;
		}
		else {
			return 0;
		}

	}

	/**
	 * Sets harmonization thread heart beat
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param int $thb		thread heart beat time stamp
	 * 
	 * @return void
	 */
	public function setHarmonizationThreadHeartBeat(string $uid, int $thb): void {
		
		// update harmonization thread id
		$this->depositUserValue($uid, 'account_harmonization_thb', $thb);

	}
}
