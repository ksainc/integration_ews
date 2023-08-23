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
	$RemoteEventsService = \OC::$server->get(\OCA\EWS\Service\Remote\RemoteEventsService::class);
	$HarmonizationService = \OC::$server->get(\OCA\EWS\Service\HarmonizationService::class);
	
	/*
	// create client
	$client = $CoreService->createClient('admin');
	$rcid = 'AQMkAGMwNTA4ZGIxLWQ1MDktNDI4Yi05ZWQxLWFmMmZmADc0NTY1MGYALgAAAzmziW6wlrFEgWK1LqkltCoBAKTqOIZoUNNLkdVBWIFk0WEAAAIBDQAAAA==';
	
	// construct event object with basic properties
	$eo = \OC::$server->get(\OCA\EWS\Objects\EventObject::class);
	$eo->Origin = 'L';
	$eo->Notes = 'Every other week on Tuesday and Thursday until 4 Weeks from start';
	$eo->StartsOn = new \DateTime('2023-8-28T9:00:00', new \DateTimeZone('America/Toronto'));
	$eo->StartsTZ = new \DateTimeZone('America/Toronto');
	$eo->EndsOn = (clone $eo->StartsOn)->modify('+1 hour');
	$eo->EndsTZ = (clone $eo->StartsTZ);
	$eo->Availability = 'Busy';
	$eo->Priority = '1';
	$eo->Sensitivity = '1';
	$eo->Occurrence->Pattern = 'A'; // Absolute
	$eo->Occurrence->Precision = 'W'; // Daily
	$eo->Occurrence->Interval = 2; // Every Other Week
	$eo->Occurrence->Concludes = (clone $eo->StartsOn)->modify('+4 Weeks');
	$eo->Occurrence->OnDayOfWeek = array(1, 3, 5);
	// generate new uuid for local
	$eo->UUID = '63b2fb65-f54d-406f-b0af-c0e531ba9937';
	$eo->Label = 'NC Weekly Occurance';

	// create remote event
	$RemoteEventsService->DataStore = $client;
	$ro = $RemoteEventsService->createCollectionItem($rcid, $eo);
	*/

	// execute initial harmonization
	//$HarmonizationService->performHarmonization($uid, 'S');

	// execute actions
	//$HarmonizationService->performActions($uid);

	// execute test
	//$CoreService->performTest($uid, 'C');

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
