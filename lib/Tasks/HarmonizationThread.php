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
	$executionDuration = 300;
	$executionCushion = 300;
	$executionPause = 60;
	$executionStart = time();
	$uid = null;

	$logger = \OC::$server->getLogger();
	$config = \OC::$server->getConfig();

	// evaluate if script was started from console
	if (php_sapi_name() == 'cli') {
		$executionMode = 'C';
		$logger->info('Harmonization thread running, as console script', ['app' => 'integration_ews']);
		echo 'Harmonization thread running, as console script' . PHP_EOL;
	}

	// evaluate running mode
	if ($executionMode == 'C') {
		// retrieve passed parameters
		$parameters = getopt("u:");
		// evaluate if user name exists
		if (isset($parameters["u"])) {
			// assign user name
			$uid = \OCA\EWS\Utile\Sanitizer::username($parameters["u"]);
		}
	}
	else {
		// evaluate if user name exists
		if (isset($_GET["u"])) {
			// assign user name
			$uid = \OCA\EWS\Utile\Sanitizer::username($_GET["u"]);
		}
	}
	
	// evaluate, if user name is present
	if (empty($uid)) {
		$logger->info('Harmonization thread ended, missing required parameters', ['app' => 'integration_ews']);
		echo 'Harmonization thread ended, missing required parameters' . PHP_EOL;
		exit(0);
	}

	$logger->info('Harmonization thread started for ' . $uid, ['app' => 'integration_ews']);
	echo 'Harmonization thread started for ' . $uid . PHP_EOL;

	// set execusion time limit plus cusion
	if ($executionMode == 'C') {
        // assign execusion limit
		@set_time_limit($executionDuration + $executionCushion);
        // the cron job must be executed with the right user
        if (!function_exists('posix_getuid')) {
            echo "The posix extensions are required - see https://www.php.net/manual/en/book.posix.php" . PHP_EOL;
            exit(1);
        }

        // evaluate if script is running as the proper system user
        $user = posix_getuid();
        $configUser = fileowner(\OC::$configDir . 'config.php');
        if ($user !== $configUser) {
            echo "Harmonization thread has to be executed with the user that owns the file config/config.php" . PHP_EOL;
            echo "Current user id: " . $user . PHP_EOL;
            echo "Owner id of config.php: " . $configUser . PHP_EOL;
            exit(1);
        }
    }

	// evaluate if nextcloud is installed
	if (!(bool) \OC::$server->getConfig()->getSystemValue('installed', false)) {
		$logger->info('Harmonization thread ended, system installed status is false', ['app' => 'integration_ews']);
		echo 'Harmonization thread ended, system installed status is false' . PHP_EOL;
		exit(0);
	}

	// evaluate if nextcloud is in maintenance mode
	if ((bool) \OC::$server->getSystemConfig()->getValue('maintenance', false)) {
		$logger->info('Harmonization thread ended, system maintenance mode is on', ['app' => 'integration_ews']);
		echo 'Harmonization thread ended, system maintenance mode is on' . PHP_EOL;
		exit(0);
	}

	// load all apps to get all api routes properly setup
	OC_App::loadApps();

	//\OC::$server->getSession()->close();

	// initialize a dummy memory session
	/*
	$session = new \OC\Session\Memory('');
	$cryptoWrapper = \OC::$server->getSessionCryptoWrapper();
	$session = $cryptoWrapper->wrapSession($session);
	\OC::$server->setSession($session);
	*/

	// initilize required services
	$CoreService = \OC::$server->get(\OCA\EWS\Service\CoreService::class);
	$HarmonizationService = \OC::$server->get(\OCA\EWS\Service\HarmonizationService::class);
	$HarmonizationThreadService = \OC::$server->get(\OCA\EWS\Service\HarmonizationThreadService::class);

	// evaluate if another harmonization thread is already running for this user
	$tid = $HarmonizationThreadService->getId($uid);
	if (getmypid() != $tid && $HarmonizationThreadService->isActive($uid, $tid)) {
		$logger->info('Harmonization thread ended, another thread is already running for ' . $uid, ['app' => 'integration_ews']);
		echo 'Harmonization thread ended, another thread is already running for ' . $uid . PHP_EOL;
		exit(0);
	}

	// retrieve and assign defaults
	$executionDuration = $HarmonizationService->getThreadDuration();
	$executionPause = $HarmonizationService->getThreadPause();
	// no longer required since we listen to events feeds
	//$harmonizePause = 120;
	//$harmonizeStart = 0;

	// execute initial harmonization
	$HarmonizationService->performHarmonization($uid, 'S');
	// connect to remote events feed(s)
	$cs = $HarmonizationService->connectEvents($uid, 60, 'CC');
	$es = $HarmonizationService->connectEvents($uid, 60, 'EC');

	while ((time() - $executionStart) < $executionDuration) {
		
		/**
		 * 
		 * TODO: evaluate if user still exists and is active
		 * 
		 */

		/**
		 * 
		 * TODO: evaluate if ews account is still connected
		 * 
		 */

		// update heart beat
		$HarmonizationThreadService->setHeartBeat($uid, time());
		 // consume events from feed and create actions
		$cs = $HarmonizationService->consumeEvents($uid, $cs->Id, $cs->Token, 'CO');
		$es = $HarmonizationService->consumeEvents($uid, $es->Id, $es->Token, 'EO');
		// execute actions
		$HarmonizationService->performActions($uid);

		// no longer required since we listen to events feed
		// execute harmonization
		/*
		if ((time() - $harmonizeStart) > $harmonizePause) {
			$harmonizeStart = time();
			$HarmonizationService->performHarmonization($uid, 'S');
		}
		*/

		// pause execution
		sleep($executionPause);
	}

	// disconnect from remote events feed(s)
	$cs = $HarmonizationService->disconnectEvents($uid, $cs->Id);
	$es = $HarmonizationService->disconnectEvents($uid, $es->Id);

	$logger->info('Harmonization thread ended for ' . $uid, ['app' => 'integration_ews']);
	echo 'Harmonization thread ended for ' . $uid . PHP_EOL;

	// spawn new harmonization thread
	$tid = $HarmonizationThreadService->launch($uid);
	// evaluate, if thread id is present
	if ($tid > 0) {
		$HarmonizationThreadService->setId($uid, $tid);
		$HarmonizationThreadService->setHeartBeat($uid, time());
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
