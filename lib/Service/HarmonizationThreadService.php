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

use OCP\IConfig;
use Psr\Log\LoggerInterface;

use OCA\EWS\AppInfo\Application;

class HarmonizationThreadService {

	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var IConfig
	 */
	private $config;

	public function __construct(string $appName, IConfig $config, LoggerInterface $logger) {
		$this->config = $config;
		$this->logger = $logger;
	}

	/**
	 * Launch new harmonization thread
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return string|null thread id on success | null on failure
	 */
	public function launch(string $uid): int {
		
		// construct command
		$command = 'php ' . 
			dirname(__DIR__) . '/Tasks/HarmonizationThread.php ' . 
			'-u' . $uid .
			' > /dev/null 2>&1 & echo $!;';
		// execute command
		$rs = shell_exec($command);
		// format response
		$tid = trim($rs);
		// evaluate if response is a thread id
		if (is_numeric($tid)) {
			return intval($tid);
		}
		else {
			return 0;
		}

	}

	/**
	 * Terminate harmonization thread
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param string $uid	nextcloud user id
	 * 
	 * @return string|null thread id on success | null on failure
	 */
	public function terminate(string $uid, int $tid = 0): void {
		
		// evaluate if thread id exists
		if ($tid = 0) {
			// retrieve thread id
			$tid = $this->getId($uid);
		}
		// evaluate if thread id exists
		if ($tid > 0) {
			// construct command
			$command = 'kill ' . $tid;
			// execute command
			$rs = shell_exec($command);
		}

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
	public function getId(string $uid): int {

		// retrieve thread id
		$tid = $this->config->getUserValue($uid, Application::APP_ID, 'account_harmonization_tid');
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
	public function setId(string $uid, int $tid): void {
		
		// update harmonization thread id
		$this->config->setUserValue($uid, Application::APP_ID, 'account_harmonization_tid', (string) $tid);

	}

	/**
	 * Gets harmonization thread heart beat
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return int|null thread heart beat time stamp if exists | null if does not exist
	 */
	public function getHeartBeat(string $uid): ?int {

		// retrieve thread heart beat
		$thb = $this->config->getUserValue($uid, Application::APP_ID, 'account_harmonization_thb');
		// return thread heart beat
		if (is_numeric($thb)) {
			return (int) $thb;
		}
		else {
			return null;
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
	public function setHeartBeat(string $uid, int $thb): void {
		
		// update harmonization thread id
		$this->config->setUserValue($uid, Application::APP_ID, 'account_harmonization_thb', $thb);

	}

	/**
	 * evaluate if harmonization thread active/live/running
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return bool true - if active thread found | false - if no active thread found 
	 */
	public function isActive(string $uid, int $tid): bool {

		if ($tid > 0) {
			// construct command
			$command = 'ps -f --no-headers -p ' . $tid;
			// execute command
			$rs = shell_exec($command);
		}
		// evaluate if we have the correct thread
		if (!empty($rs) && str_contains($rs, $tid) && str_contains($rs, $uid)) {
			return true;
		}
		else {
			return false;
		}

	}
	
}
