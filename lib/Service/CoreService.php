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

use stdClass;
use DateTime;
use Exception;
use Throwable;
use OCP\IL10N;
use Psr\Log\LoggerInterface;
use OCP\Notification\IManager as INotificationManager;
use OCP\BackgroundJob\IJobList;
use OCA\DAV\CardDAV\CardDavBackend;
use OCA\DAV\CalDAV\CalDavBackend;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Db\ActionMapper;
use OCA\EWS\Service\ConfigurationService;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\ContactsService;
use OCA\EWS\Service\EventsService;
use OCA\EWS\Service\HarmonizationThreadService;
use OCA\EWS\Service\Local\LocalContactsService;
use OCA\EWS\Service\Local\LocalEventsService;
use OCA\EWS\Service\Remote\RemoteContactsService;
use OCA\EWS\Service\Remote\RemoteEventsService;
use OCA\EWS\Service\Remote\RemoteCommonService;
use OCA\EWS\Tasks\HarmonizationLauncher;

class CoreService {

	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var IJobList
	 */
	private IJobList $TaskService;
	/**
	 * @var INotificationManager
	 */
	private $notificationManager;
	/**
	 * @var string|null
	 */
	private $userId;
	/**
	 * @var ActionMapper
	 */
	private $ActionMapper;
	/**
	 * @var ConfigurationService
	 */
	private $ConfigurationService;
	/**
	 * @var CorrelationsService
	 */
	private $CorrelationsService;
	/**
	 * @var LocalContactsService
	 */
	private $LocalContactsService;
	/**
	 * @var LocalEventsService
	 */
	private $LocalEventsService;
	/**
	 * @var RemoteContactsService
	 */
	private $RemoteContactsService;
	/**
	 * @var RemoteEventsService
	 */
	private $RemoteEventsService;
	/**
	 * @var CardDavBackend
	 */
	private $LocalContactsStore;
	/**
	 * @var CalDavBackend
	 */
	private $LocalEventsStore;
	/**
	 * @var EWSClient
	 */
	private $RemoteStore;

	public function __construct (string $appName,
								LoggerInterface $logger,
								IJobList $TaskService,
								INotificationManager $notificationManager,
								ActionMapper $ActionMapper,
								ConfigurationService $ConfigurationService,
								CorrelationsService $CorrelationsService,
								HarmonizationThreadService $HarmonizationThreadService,
								LocalContactsService $LocalContactsService,
								LocalEventsService $LocalEventsService,
								RemoteContactsService $RemoteContactsService,
								RemoteEventsService $RemoteEventsService,
								RemoteCommonService $RemoteCommonService,
								ContactsService $ContactsService,
								EventsService $EventsService,
								CardDavBackend $LocalContactsStore,
								CalDavBackend $LocalEventsStore) {
		$this->logger = $logger;
		$this->TaskService = $TaskService;
		$this->notificationManager = $notificationManager;
		$this->ActionMapper = $ActionMapper;
		$this->ConfigurationService = $ConfigurationService;
		$this->CorrelationsService = $CorrelationsService;
		$this->HarmonizationThreadService = $HarmonizationThreadService;
		$this->LocalContactsService = $LocalContactsService;
		$this->LocalEventsService = $LocalEventsService;
		$this->RemoteContactsService = $RemoteContactsService;
		$this->RemoteEventsService = $RemoteEventsService;
		$this->RemoteCommonService = $RemoteCommonService;
		$this->ContactsService = $ContactsService;
		$this->EventsService = $EventsService;
		$this->LocalContactsStore = $LocalContactsStore;
		$this->LocalEventsStore = $LocalEventsStore;

	}

	/**
	 * Connects to account to verify details, on success saves details to user settings
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid				nextcloud user id
	 * @param string $account_provider	host name or ip
	 * @param string $account_id		account username
	 * @param string $account_secret	account secret
	 * 
	 * @return bool
	 */
	public function connectAccount(string $uid, string $account_provider, string $account_id, string $account_secret, bool $validate = true): bool {

		// define connect status place holder
		$connected = false;
		// evaluate validate flag
		if ($validate) {
			// construct remote data store client
			$RemoteStore = new EWSClient($account_provider, $account_id, $account_secret, 'Exchange2007');
			// retrieve root folder attributes
			$rs = $this->RemoteCommonService->fetchFolder($RemoteStore, 'root', true, 'A');
			// evaluate server response
			if (isset($rs)) {
				// extract server version from response
				preg_match_all(
					'/<ServerVersionInfo[^>]*?\sVersion=(["\'])?((?:.(?!\1|>))*.?)\1?/',
					$RemoteStore->getClient()->__last_response,
					$match
				);
				$account_protocol = $match[2][0];
				$connected = true;
			}
		}
		else {
			$connected = true;
			$account_protocol = 'Exchange2010';
		}
		// evaluate connect status
		if ($connected) {
			// retrieve default configuration
			$uc = $this->ConfigurationService->retrieveUser($uid);
			// update configuration
			$uc['account_provider'] = $account_provider;
			$uc['account_id'] = $account_id;
			$uc['account_secret'] = $account_secret;
			$uc['account_protocol'] = $account_protocol;
			$uc['account_connected'] = '1';
			// deposit configuration to datastore
			$uc = $this->ConfigurationService->depositUser($uid, $uc);

			// register harmonization task
			$this->TaskService->add(\OCA\EWS\Tasks\HarmonizationLauncher::class, ['uid' => $uid]);

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Removes all users settings, correlations, etc for specific user
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return void
	 */
	public function disconnectAccount(string $uid): void {
		
		// deregister task
		$this->TaskService->remove(\OCA\EWS\Tasks\HarmonizationLauncher::class, ['uid' => $uid]);
		// terminate harmonization thread
		$this->HarmonizationThreadService->terminate($uid);
		// delete correlations
		$this->CorrelationsService->deleteByUserId($uid);
		// delete actions
		$this->ActionMapper->deleteByUserId($uid);
		// delete configuration
		$this->ConfigurationService->destroyUser($uid);

	}

	/**
	 * Retrieves local collections for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of local collection(s) and attributes
	 */
	public function fetchLocalCollections(string $uid): array {

		// assign local data store
		$this->LocalContactsService->DataStore = $this->LocalContactsStore;
		$this->LocalEventsService->DataStore = $this->LocalEventsStore;
		// construct response object
		$response = ['ContactCollections' => [], 'EventCollections' => []];
		// retrieve local collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			$response['ContactCollections'] = $this->LocalContactsService->listCollections($uid);;
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			$response['EventCollections'] = $this->LocalEventsService->listCollections($uid);
		}
		// return response
		return $response;

	}

	/**
	 * Retrieves remote collections for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of remote collection(s) and attributes
	 */
	public function fetchRemoteCollections(string $uid): array {
		
		// create remote store client
		$RemoteStore = $this->createClient($uid);
		// assign remote data store and settings
		$this->RemoteContactsService->DataStore = $RemoteStore;
		$this->RemoteEventsService->DataStore = $RemoteStore;
		
		// construct response object
		$response = ['ContactCollections' => [], 'EventCollections' => []];
		// retrieve local collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			$response['ContactCollections'] = $this->RemoteContactsService->listCollections();
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			$response['EventCollections'] = $this->RemoteEventsService->listCollections();
		}
		// return response
		return $response;

	}

	/**
	 * Retrieves collection correlations for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return array of collection correlation(s) and attributes
	 */
	public function fetchCorrelations(string $uid): array {

		// construct response object
		$response = ['ContactCorrelations' => [], 'EventCorrelations' => []];
		// retrieve local collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			$response['ContactCorrelations'] = $this->CorrelationsService->findByType($uid, 'CC');
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			$response['EventCorrelations'] = $this->CorrelationsService->findByType($uid, 'EC');
		}
		// return response
		return $response;

	}

	/**
	 * Deposit collection correlations for all modules
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * @param array $cc		contacts collection(s) correlations
	 * @param array $ec		events collection(s) correlations
	 * 
	 * @return array of collection correlation(s) and attributes
	 */
	public function depositCorrelations(string $uid, array $cc, array $ec): void {
		
		// terminate harmonization thread, in case the user changed any correlations
		$this->HarmonizationThreadService->terminate($uid);
		// deposit contacts correlations
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			foreach ($cc as $entry) {
				if (!empty($entry['action'])) {
					try {
						switch ($entry['action']) {
							case 'D':
								$cc = $this->CorrelationsService->fetch($entry['id']);
								if ($this->UserId == $entry['uid']) {
									$this->CorrelationsService->deleteByCollectionId($cc->getuid(), $cc->getloid(), $cc->getroid());
									$this->CorrelationsService->delete($cc);
								}
								break;
							case 'C':
								$cc = new \OCA\EWS\Db\Correlation();
								$cc->settype('CC'); // Correlation Type
								$cc->setuid($uid); // User ID
								$cc->setloid($entry['loid']); // Local ID
								$cc->setroid($entry['roid']); // Remote ID
								$this->CorrelationsService->create($cc);
								break;
							case 'U':
								$cc = $this->CorrelationsService->fetch($entry['id']);
								if ($this->UserId == $entry['uid']) {
									$cc->settype('CC'); // Correlation Type
									$cc->setloid($entry['loid']); // Local ID
									$cc->setroid($entry['roid']); // Remote ID
									$this->CorrelationsService->update($cc);
								}
								break;
						}
					}
					catch (Exception $e) {
						
					}
				}
			}
		}
		// deposit events correlations
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			foreach ($ec as $entry) {
				if (!empty($entry['action'])) {
					try {
						switch ($entry['action']) {
							case 'D':
								$cc = $this->CorrelationsService->fetch($entry['id']);
								if ($this->UserId == $entry['uid']) {
									$this->CorrelationsService->deleteByCollectionId($cc->getuid(), $cc->getloid(), $cc->getroid());
									$this->CorrelationsService->delete($cc);
								}
								break;
							case 'C':
								$cc = new \OCA\EWS\Db\Correlation();
								$cc->settype('EC'); // Correlation Type
								$cc->setuid($uid); // User ID
								$cc->setloid($entry['loid']); // Local ID
								$cc->setroid($entry['roid']); // Remote ID
								$this->CorrelationsService->create($cc);
								break;
							case 'U':
								$cc = $this->CorrelationsService->fetch($entry['id']);
								if ($this->UserId == $entry['uid']) {
									$cc->settype('EC'); // Correlation Type
									$cc->setloid($entry['loid']); // Local ID
									$cc->setroid($entry['roid']); // Remote ID
									$this->CorrelationsService->update($cc);
								}
								break;
						}
					}
					catch (Exception $e) {
						
					}
				}
			}
		}

	}

	/**
	 * Create remote data store client (EWS Client)
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return EWSClient
	 */
	public function createClient(string $uid): EWSClient {

		if (!$this->RemoteStore instanceof EWSClient) {
			// retrieve connection information
			$ac = $this->ConfigurationService->retrieveAuthentication($uid);
			// construct remote data store client
			$this->RemoteStore = new EWSClient($ac['account_provider'], $ac['account_id'], $ac['account_secret'], $ac['account_protocol']);
		}

		return $this->RemoteStore;

	}

	/**
	 * Destroys remote data store client (EWS Client)
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param EWSClient $Client	nextcloud user id
	 * 
	 * @return void
	 */
	public function destroyClient(EWSClient $Client): void {
		
		// destory remote data store client
		$Client = null;

	}
	
	/**
	 * publish user notification
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid		nextcloud user id
	 * @param array $subject	notification type
	 * @param array $params		notification paramaters to pass
	 * 
	 * @return array of collection correlation(s) and attributes
	 */
	public function publishNotice(string $uid, string $subject, array $params): void {
		// construct notification object
		$notification = $this->notificationManager->createNotification();
		// assign attributes
		$notification->setApp(Application::APP_ID)
			->setUser($uid)
			->setDateTime(new DateTime())
			->setObject('ews', 'ews')
			->setSubject($subject, $params);
		// submit notification
		$this->notificationManager->notify($notification);
	}

	public function performTest(string $uid, string $action): void {

		//$this->performActions($uid);
		//return;

		try {
			// retrieve preferences
			$settings = $this->ConfigurationService->retrieveUser($uid);
			$settings = new \OCA\EWS\Objects\SettingsObject($settings);
			// create remote store client
			$RemoteStore = $this->createClient($uid);
			// Test Contacts
			$this->ContactsService->RemoteStore = $RemoteStore;
			$this->ContactsService->Settings = $settings;
			$result = $this->ContactsService->performTest($action);
			// Test Events
			$this->EventsService->RemoteStore = $RemoteStore;
			$this->EventsService->Settings = $settings;
			$result = $this->EventsService->performTest($action);
			// destroy remote store client
			$this->destroyClient($RemoteStore);
		} catch (Exception $e) {
			$result = [
					'error' => 'Unknown Test failure:' . $e,
			];
		}
	}
	
}
