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

require_once __DIR__ . '/../../../../lib/versioncheck.php';

try {
	require_once __DIR__ . '/../../../../lib/base.php';

	// assign defaults
	$executionMode = 'S';
	$executionDuration = 3600;
	$executionPause = 60;
	$uid = null;

	$logger = \OC::$server->getLogger();

	// evaluate if script was started from console
	if (php_sapi_name() == 'cli') {
		$executionMode = 'C';

		$logger->info("Harmonization thread executed from console", ['app' => 'integration_ews']);
		echo "Harmonization thread executed from console" . PHP_EOL;

		// evaluate if required function exists
        if (!function_exists('posix_getuid')) {
			$logger->info("Harmonization thread failed missing required posix extensions - see https://www.php.net/manual/en/book.posix.php", ['app' => 'integration_ews']);
            echo "Harmonization thread failed missing required posix extensions - see https://www.php.net/manual/en/book.posix.php" . PHP_EOL;
            exit(1);
        }

        // evaluate if script is running as the proper system user
        $user = posix_getuid();
        $configUser = fileowner(\OC::$configDir . 'config.php');
        if ($user !== $configUser) {
			$logger->info(
				"Harmonization thread failed has to be executed with the user that owns the file config/config.php" .
            	 "Current user id: $user Owner id of config.php: $configUser" . PHP_EOL
				, ['app' => 'integration_ews']);
            echo "Harmonization thread failed has to be executed with the user that owns the file config/config.php" .
            	 "Current user id: $user Owner id of config.php: $configUser" . PHP_EOL;
            exit(1);
        }
	}
	// evaluate if script was started from localhost
	elseif ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' && $_SERVER['REMOTE_ADDR'] != '::1') {
		$logger->info("Harmonization thread failed can only be executed from the console or localhost", ['app' => 'integration_ews']);
		echo "Harmonization thread failed can only be executed from the console or localhost" . PHP_EOL;
		exit(1);
	}

	// evaluate running mode
	if ($executionMode == 'C') {
		// retrieve passed parameters
		$parameters = getopt("u:");
		// evaluate if user parameter exists
		if (isset($parameters["u"])) {
			// assign user id
			$uid = \OCA\EWS\Utile\Sanitizer::username($parameters["u"]);
		}
	}
	else {
		// evaluate if user parameter exists
		if (isset($_GET["u"])) {
			// assign user id
			$uid = \OCA\EWS\Utile\Sanitizer::username($_GET["u"]);
		}
	}
	
	// evaluate, if user parameter is present
	if (empty($uid)) {
		$logger->info("Harmonization thread failed missing required parameters", ['app' => 'integration_ews']);
		echo "Harmonization thread failed missing required parameters" . PHP_EOL;
		exit(0);
	}

	// evaluate if nextcloud is installed
	if (!(bool) \OC::$server->getConfig()->getSystemValue('installed', false)) {
		$logger->info("Harmonization thread failed system installed status is false", ['app' => 'integration_ews']);
		echo "Harmonization thread failed system installed status is false" . PHP_EOL;
		exit(0);
	}

	// evaluate if nextcloud is in maintenance mode
	if ((bool) \OC::$server->getSystemConfig()->getValue('maintenance', false)) {
		$logger->info("Harmonization thread failed system maintenance mode is on", ['app' => 'integration_ews']);
		echo "Harmonization thread failed system maintenance mode is on" . PHP_EOL;
		exit(0);
	}

	// assign execusion limit
	@set_time_limit($executionDuration);

	// load all apps to get all api routes properly setup
	OC_App::loadApps();

	// initilize required services
	$ConfigurationService = \OC::$server->get(\OCA\EWS\Service\ConfigurationService::class);
	$HarmonizationService = \OC::$server->get(\OCA\EWS\Service\HarmonizationService::class);
	$HarmonizationThreadService = \OC::$server->get(\OCA\EWS\Service\HarmonizationThreadService::class);

	// evaluate if another harmonization thread is already running for this user
	$tid = $HarmonizationThreadService->getId($uid);
	if (getmypid() != $tid && $HarmonizationThreadService->isActive($uid, $tid)) {
		$logger->info("Harmonization thread failed another thread is already running for $uid", ['app' => 'integration_ews']);
		echo "Harmonization thread failed another thread is already running for $uid" . PHP_EOL;
		exit(0);
	}

	// evaluate if user account is connected
	if (!$ConfigurationService->isAccountConnected($uid)) {
		$logger->info("Harmonization thread failed user $uid does not have a connected account", ['app' => 'integration_ews']);
		echo "Harmonization thread failed user $uid does not have a connected account" . PHP_EOL;
		exit(0);
	}

	// retrieve and assign defaults
	$executionDuration = $ConfigurationService->getHarmonizationThreadDuration();
	$executionPause = $ConfigurationService->getHarmonizationThreadPause();
	$executionStart = time();
	$executionConclusion = '';

	$logger->info("Harmonization thread started for $uid", ['app' => 'integration_ews']);
	echo "Harmonization thread started for $uid" . PHP_EOL;

	// execute initial harmonization
	$HarmonizationService->performHarmonization($uid, 'S');
	// connect to remote events queue(s)
	$cs = $HarmonizationService->connectEvents($uid, 15, 'CC');
	$es = $HarmonizationService->connectEvents($uid, 15, 'EC');
	$ts = $HarmonizationService->connectEvents($uid, 15, 'TC');

	while ((time() - $executionStart) < $executionDuration) {
		
		// update thread heart beat
		$HarmonizationThreadService->setHeartBeat($uid, time());

		/**
		 * 
		 * TODO: evaluate if user still exists and is active
		 * 
		 */
		// evaluate if nextcloud is in maintenance mode
		if ((bool) \OC::$server->getSystemConfig()->getValue('maintenance', false)) {
			$executionConclusion = 'EM';
			break;
		}
		// evaluate if user account is stil connected
		if (!$ConfigurationService->isAccountConnected($uid)) {
			$executionConclusion = 'EA';
			break;
		}

		// consume events from feed(s)
		if (isset($cs)) {
			$cs = $HarmonizationService->consumeEvents($uid, $cs->Id, $cs->Token, 'CC');
		}
		if (isset($es)) {
			$es = $HarmonizationService->consumeEvents($uid, $es->Id, $es->Token, 'EC');
		}
		if (isset($ts)) {
			$ts = $HarmonizationService->consumeEvents($uid, $ts->Id, $ts->Token, 'TC');
		}
		
		// execute actions
		$HarmonizationService->performLiveHarmonization($uid);

		$executionConclusion = 'N';

		// pause execution
		sleep($executionPause);
	}

	// disconnect from remote events queue(s)
	if (isset($cs)) {
		$cs = $HarmonizationService->disconnectEvents($uid, $cs->Id);
	}
	if (isset($es)) {
		$es = $HarmonizationService->disconnectEvents($uid, $es->Id);
	}
	if (isset($ts)) {
		$ts = $HarmonizationService->disconnectEvents($uid, $ts->Id);
	}

	$logger->info("Harmonization thread ended for $uid", ['app' => 'integration_ews']);
	echo "Harmonization thread ended for $uid" . PHP_EOL;

	if ($executionMode == 'C' && $executionConclusion == 'N') {
		// spawn new harmonization thread
		$tid = $HarmonizationThreadService->launch($uid);
		// evaluate, if thread id is present
		if ($tid > 0) {
			$HarmonizationThreadService->setId($uid, $tid);
			$HarmonizationThreadService->setHeartBeat($uid, time());
		}
	}

	exit();
} catch (Exception $ex) {
	$logger->logException($ex, ['app' => 'integration_ews']);
	$logger->info('Harmonization thread ended unexpectedly', ['app' => 'integration_ews']);
	echo $ex . PHP_EOL;
	exit(1);
} catch (Error $ex) {
	$logger->logException($ex, ['app' => 'integration_ews']);
	$logger->info('Harmonization thread ended unexpectedly', ['app' => 'integration_ews']);
	echo $ex . PHP_EOL;
	exit(1);
}
