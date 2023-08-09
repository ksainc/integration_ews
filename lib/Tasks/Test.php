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
	$uid = null;

	$logger = \OC::$server->getLogger();
	$config = \OC::$server->getConfig();

	// evaluate if script was started from console
	if (php_sapi_name() == 'cli') {
		$executionMode = 'C';
		$logger->info('Test running, as console script', ['app' => 'integration_ews']);
		echo 'Test running, as console script' . PHP_EOL;
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
		$logger->info('Test ended, missing required parameters', ['app' => 'integration_ews']);
		echo 'Test ended, missing required parameters' . PHP_EOL;
		exit(0);
	}

	$logger->info('Test started for ' . $uid, ['app' => 'integration_ews']);
	echo 'Test started for ' . $uid . PHP_EOL;

	// load all apps to get all api routes properly setup
	OC_App::loadApps();

	// initilize required services
	$ConfigurationService = \OC::$server->get(\OCA\EWS\Service\ConfigurationService::class);
	$CoreService = \OC::$server->get(\OCA\EWS\Service\CoreService::class);
	$HarmonizationService = \OC::$server->get(\OCA\EWS\Service\HarmonizationService::class);
	$HarmonizationThreadService = \OC::$server->get(\OCA\EWS\Service\HarmonizationThreadService::class);

	// retrieve user configuration
	$configuration = $ConfigurationService->retrieveUser($uid);
	// execute initial harmonization
	$HarmonizationService->performHarmonization($uid, 'S');

	//$cs = $HarmonizationService->connectEvents($uid, 5, 'CC');
	//$es = $HarmonizationService->connectEvents($uid, 5, 'EC');
	
	//$cs = $HarmonizationService->consumeEvents($uid, $cs->Id, $cs->Token, 'CO');
	//$es = $HarmonizationService->consumeEvents($uid, $es->Id, $es->Token, 'EO');

	//$cs = $HarmonizationService->disconnectEvents($uid, $cs->Id);
	//$es = $HarmonizationService->disconnectEvents($uid, $es->Id);

	// execute actions
	//$HarmonizationService->performActions($uid);

	$logger->info('Test ended for ' . $uid, ['app' => 'integration_ews']);
	echo 'Test ended for ' . $uid . PHP_EOL;

	exit();
} catch (Exception $ex) {
	$logger->logException($ex, ['app' => 'integration_ews']);
	$logger->info('Test ended unexpectedly', ['app' => 'integration_ews']);
	echo $ex . PHP_EOL;
	exit(1);
} catch (Error $ex) {
	$logger->logException($ex, ['app' => 'integration_ews']);
	$logger->info('Test ended unexpectedly', ['app' => 'integration_ews']);
	echo $ex . PHP_EOL;
	exit(1);
}
