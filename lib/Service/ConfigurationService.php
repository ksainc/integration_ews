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
use Psr\Log\LoggerInterface;

use OCP\IConfig;
use OCP\Security\ICrypto;

use OCA\EWS\AppInfo\Application;

class ConfigurationService {

	const ProviderAlternate = 'A';
	const ProviderMS365 = 'MS365';

	/**
	 * Default System Configuration 
	 * @var array
	 * */
	private const _SYSTEM = [
		'harmonization_mode' => 'P',
		'harmonization_thread_duration' => '3600',
		'harmonization_thread_pause' => '15',
		'ms365_tenant_id' => '',
		'ms365_application_id' => '',
		'ms365_application_secret' => '',
	];

	/**
	 * Default System Secure Parameters 
	 * @var array
	 * */
	private const _SYSTEM_SECURE = [
		'ms365_tenant_id' => 1,
		'ms365_application_id' => 1,
		'ms365_application_secret' => 1,
	];

	/**
	 * Default User Configuration 
	 * @var array
	 * */
	private const _USER = [
		'account_provider' => 'A',
		'account_server' => '',
		'account_id' => '',
		'account_secret' => '',
		'account_name' => '',
		'account_protocol' => 'Exchange2007',
		'account_connected' => '0',
		'account_harmonization_state' => '0',
		'account_harmonization_start' => '0',
		'account_harmonization_end' => '0',
		'account_harmonization_tid' => '0',
		'account_harmonization_thb' => '0',
		'account_oauth_access' => '',
		'account_oauth_expiry' => '0',
		'account_oauth_refresh' => '',
		'contacts_harmonize' => '5',
		'contacts_prevalence' => 'R',
		'contacts_presentation' => '',
		'events_harmonize' => '5',
		'events_prevalence' => 'R',
		'events_timezone' => '',
		'events_attachment_path' => '/Calendar',
		'tasks_harmonize' => '5',
		'tasks_prevalence' => 'R',
		'tasks_attachment_path' => '/Tasks',
	];

	/**
	 * Default User Secure Parameters 
	 * @var array
	 * */
	private const _USER_SECURE = [
		'account_secret' => 1,
		'account_oauth_access' => 1,
		'account_oauth_refresh' => 1,
	];

	/** @var LoggerInterface */
	private $_logger;

	/** @var IConfig */
	private $_ds;
	
	/** @var ICrypto */
	private $_cs;

	public function __construct(LoggerInterface $logger, IConfig $config, ICrypto $crypto)
	{
		$this->logger = $logger;
		$this->_ds = $config;
		$this->_cs = $crypto;
	}

	/**
	 * Retrieves account provider
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return string acount provider id
	 */
	public function retrieveProvider(string $uid): string {
		
		// retrieve and return account provider
		return $this->retrieveUserValue($uid, 'account_provider');;

	}

	/**
	 * Deposit accout provider
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param string $id		account provider id 
	 * 
	 * @return void
	 */
	public function depositProvider(string $uid, string $id): void {
		
		// deposit account provider
		$this->depositUserValue($uid, 'account_provider', $id);

	}

	/**
	 * Retrieves user basic authentication parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of key/value pairs, of parameters
	 */
	public function retrieveAuthenticationBasic(string $uid): array {
		
		// retrieve user authentication parameters
		$parameters = [];
		$parameters['account_server'] = $this->retrieveUserValue($uid, 'account_server');
		$parameters['account_protocol'] = $this->retrieveUserValue($uid, 'account_protocol');
		$parameters['account_id'] = $this->retrieveUserValue($uid, 'account_id');
		$parameters['account_secret'] = $this->retrieveUserValue($uid, 'account_secret');
		// return configuration parameters
		return $parameters;

	}

	/**
	 * Deposit user basic authentication parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid			nextcloud user id
	 * @param string $server		FQDN or IP
	 * @param string $protocol		account protocol version
	 * @param string $id			account username
	 * @param string $secret		account secret
	 * 
	 * @return void
	 */
	public function depositAuthenticationBasic(string $uid, string $server, string $protocol, string $id, string $secret, string $name = ''): void {
		
		// deposit user authentication parameters
		$this->depositUserValue($uid, 'account_server', $server);
		$this->depositUserValue($uid, 'account_protocol', $protocol);
		$this->depositUserValue($uid, 'account_id', $id);
		$this->depositUserValue($uid, 'account_secret', $secret);
		$this->depositUserValue($uid, 'account_name', $id);

	}

	/**
	 * Retrieves user oauth authentication parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of key/value pairs, of parameters
	 */
	public function retrieveAuthenticationOAuth(string $uid): array {
		
		// retrieve user authentication parameters
		$parameters = [];
		$parameters['account_server'] = $this->retrieveUserValue($uid, 'account_server');
		$parameters['account_protocol'] = $this->retrieveUserValue($uid, 'account_protocol');
		$parameters['account_oauth_access'] = $this->retrieveUserValue($uid, 'account_oauth_access');
		$parameters['account_oauth_expiry'] = $this->retrieveUserValue($uid, 'account_oauth_expiry');
		$parameters['account_oauth_refresh'] = $this->retrieveUserValue($uid, 'account_oauth_refresh');
		// return configuration parameters
		return $parameters;

	}

	/**
	 * Deposit user oauth authentication parameters
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid			nextcloud user id
	 * @param string $server		FQDN or IP 
	 * @param string $token			oauth token
	 * @param string $expiry		oauth expiry timestamp
	 * @param string $refresh		oauth refresh code
	 * 
	 * @return void
	 */
	public function depositAuthenticationOAuth(string $uid, string $server, string $protocol, string $token, string $expiry, string $refresh, string $id = '', string $name = ''): void {
		
		// deposit user oauth authentication parameters
		$this->depositUserValue($uid, 'account_server', $server);
		$this->depositUserValue($uid, 'account_protocol', $protocol);
		$this->depositUserValue($uid, 'account_oauth_access', $token);
		$this->depositUserValue($uid, 'account_oauth_expiry', $expiry);
		$this->depositUserValue($uid, 'account_oauth_refresh', $refresh);
		$this->depositUserValue($uid, 'account_id', $id);
		$this->depositUserValue($uid, 'account_name', $name);

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
			// retrieve all user configuration keys
			$keys = array_keys(self::_USER);
			// retrieve all user configuration values
			foreach ($keys as $entry) {
				$parameters[$entry] = $this->retrieveUserValue($uid, $entry);
			}
			// retrieve system parameters
			$parameters['system_timezone'] = date_default_timezone_get();
			$parameters['system_contacts'] = $this->isContactsAppAvailable();
			$parameters['system_events'] = $this->isCalendarAppAvailable();
			$parameters['system_tasks'] = $this->isTasksAppAvailable();
			$parameters['user_id'] = $uid;
			// user default time zone
			$v = $this->_ds->getUserValue($uid, 'core', 'timezone');
			if (!empty($v)) {
				$parameters['user_timezone'] = $v;
			}
			// user events attachment path
			$v = $this->_ds->getUserValue($uid, 'dav', 'attachmentsFolder');
			if (!empty($v)) {
				$parameters['events_attachment_path'] = $v;
			}
		}
		else {
			// retrieve specific user configuration values
			foreach ($keys as $entry) {
				$parameters[$entry] = $this->retrieveUserValue($uid, $entry);
			}
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
			// evaluate if parameter is on the secure list
			if (isset(self::_USER_SECURE[$key])) {
				$value = $this->_cs->decrypt($value);
			}
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
		
		// trim whitespace
		$value = trim($value);
		// evaluate if parameter is on the secure list
		if (isset(self::_USER_SECURE[$key]) && !empty($value)) {
			$value = $this->_cs->encrypt($value);
		}
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
	public function destroyUser(string $uid, ?array $keys = null): void {

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
			if (isset(self::_SYSTEM_SECURE[$key])) {
				$value = $this->_cs->decrypt($value);
			}
			// return configuration parameter value
			return $value;
		} else {
			// return default system configuration value
			return self::_SYSTEM[$key];
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
		
		// trim whitespace
		$value = trim($value);
		// evaluate if parameter is on the secure list
		if (isset(self::_SYSTEM_SECURE[$key]) && !empty($value)) {
			$value = $this->_cs->encrypt($value);
		}
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
	public function destroySystem(?array $keys = null): void {

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
				case 'events_attachment_path':
					$o->EventsAttachmentPath = $value;
					break;
				case 'tasks_harmonize':
					$o->TasksHarmonize = $value;
					break;
				case 'tasks_prevalence':
					$o->TasksPrevalence = $value;
					break;
				case 'tasks_attachment_path':
					$o->TasksAttachmentPath = $value;
					break;
				case 'account_provider':
					$o->AccountProvider = $value;
					break;
				case 'account_server':
					$o->AccountServer = $value;
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
	 * Gets harmonization state
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return bool
	 */
	public function getHarmonizationState(string $uid): bool {

		// retrieve state
		return (bool) $this->retrieveUserValue($uid, 'account_harmonization_state');

	}

	/**
	 * Sets harmonization state
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param bool $state		harmonization state (true/false)
	 * 
	 * @return void
	 */
	public function setHarmonizationState(string $uid, bool $state): void {
		
		// deposit state
		$this->depositUserValue($uid, 'account_harmonization_state', $state);

	}

	
	/**
	 * Gets harmonization start
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return int
	 */
	public function getHarmonizationStart(string $uid): int {

		// return time stamp
		return (int) $this->retrieveUserValue($uid, 'account_harmonization_start');

	}

	/**
	 * Sets harmonization start
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * 
	 * @return void
	 */
	public function setHarmonizationStart(string $uid): void {
		
		// deposit time stamp
		$this->depositUserValue($uid, 'account_harmonization_start', time());

	}

	/**
	 * Gets harmonization end
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return int
	 */
	public function getHarmonizationEnd(string $uid): int {

		// return time stamp
		return (int) $this->retrieveUserValue($uid, 'account_harmonization_end');

	}

	/**
	 * Sets harmonization end
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * 
	 * @return void
	 */
	public function setHarmonizationEnd(string $uid): void {
		
		// deposit time stamp
		$this->depositUserValue($uid, 'account_harmonization_end', time());

	}

	/**
	 * Gets harmonization heart beat
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return int
	 */
	public function getHarmonizationHeartBeat(string $uid): int {

		// return time stamp
		return (int) $this->retrieveUserValue($uid, 'account_harmonization_hb');

	}

	/**
	 * Sets harmonization heart beat
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * 
	 * @return void
	 */
	public function setHarmonizationHeartBeat(string $uid): void {
		
		// deposit time stamp
		$this->depositUserValue($uid, 'account_harmonization_hb', time());

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

	/**
	 * retrieve contacts app status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return bool
	 */
	public function isMailAppAvailable(): bool {

		// retrieve contacts app status
		$status = $this->_ds->getAppValue('mail', 'enabled');
		// evaluate status
		if ($status == 'yes') {
			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * retrieve contacts app status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return bool
	 */
	public function isContactsAppAvailable(): bool {

		// retrieve contacts app status
		$status = $this->_ds->getAppValue('contacts', 'enabled');
		// evaluate status
		if ($status == 'yes') {
			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * retrieve calendar app status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return bool
	 */
	public function isCalendarAppAvailable(): bool {

		// retrieve calendar app status
		$status = $this->_ds->getAppValue('calendar', 'enabled');
		// evaluate status
		if ($status == 'yes') {
			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * retrieve task app status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return bool
	 */
	public function isTasksAppAvailable(): bool {

		// retrieve calendar app status
		$status = $this->_ds->getAppValue('tasks', 'enabled');
		// evaluate status
		if ($status == 'yes') {
			return true;
		}
		else {
			return false;
		}

	}

	/**
	 * retrieve account status
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return bool
	 */
	public function isAccountConnected(string $uid): bool {

		// retrieve account status
		return filter_var($this->retrieveUserValue($uid, 'account_connected'), FILTER_VALIDATE_BOOLEAN);

	}

	/**
	 * encrypt string
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string
	 */
	public function encrypt(string $value): string {

		return $this->_cs->encrypt($value);

	}

	/**
	 * decrypt string
	 * 
	 * @since Release 1.0.0
	 * 
	 * @return string
	 */
	public function decrypt(string $value): string {

		return $this->_cs->decrypt($value);

	}
}
