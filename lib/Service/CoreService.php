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
use OCA\EWS\Components\EWS\Autodiscover;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Service\ConfigurationService;
use OCA\EWS\Service\CorrelationsService;
use OCA\EWS\Service\ContactsService;
use OCA\EWS\Service\EventsService;
use OCA\EWS\Service\TasksService;
use OCA\EWS\Service\HarmonizationThreadService;
use OCA\EWS\Service\Local\LocalContactsService;
use OCA\EWS\Service\Local\LocalEventsService;
use OCA\EWS\Service\Local\LocalTasksService;
use OCA\EWS\Service\Remote\RemoteContactsService;
use OCA\EWS\Service\Remote\RemoteEventsService;
use OCA\EWS\Service\Remote\RemoteTasksService;
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
	 * @var ConfigurationService
	 */
	private $ConfigurationService;
	/**
	 * @var CorrelationsService
	 */
	private $CorrelationsService;
	/**
	 * @var HarmonizationThreadService
	 */
	private $HarmonizationThreadService;
	/**
	 * @var LocalContactsService
	 */
	private $LocalContactsService;
	/**
	 * @var LocalEventsService
	 */
	private $LocalEventsService;
	/**
	 * @var LocalTasksService
	 */
	private $LocalTasksService;
	/**
	 * @var RemoteContactsService
	 */
	private $RemoteContactsService;
	/**
	 * @var RemoteEventsService
	 */
	private $RemoteEventsService;
	/**
	 * @var RemoteTasksService
	 */
	private $RemoteTasksService;
	/**
	 * @var RemoteCommonService
	 */
	private $RemoteCommonService;
	/**
	 * @var ContactsService
	 */
	private $ContactsService;
	/**
	 * @var EventsService
	 */
	private $EventsService;
	/**
	 * @var TasksService
	 */
	private $TasksService;
	/**
	 * @var CardDavBackend
	 */
	private $LocalContactsStore;
	/**
	 * @var CalDavBackend
	 */
	private $LocalEventsStore;
	/**
	 * @var CalDavBackend
	 */
	private $LocalTasksStore;
	/**
	 * @var EWSClient
	 */
	private $RemoteStore;

	public function __construct (string $appName,
								LoggerInterface $logger,
								IJobList $TaskService,
								INotificationManager $notificationManager,
								ConfigurationService $ConfigurationService,
								CorrelationsService $CorrelationsService,
								HarmonizationThreadService $HarmonizationThreadService,
								LocalContactsService $LocalContactsService,
								LocalEventsService $LocalEventsService,
								LocalTasksService $LocalTasksService,
								RemoteContactsService $RemoteContactsService,
								RemoteEventsService $RemoteEventsService,
								RemoteTasksService $RemoteTasksService,
								RemoteCommonService $RemoteCommonService,
								ContactsService $ContactsService,
								EventsService $EventsService,
								TasksService $TasksService,
								CardDavBackend $CardDavBackend,
								CalDavBackend $CalDavBackend) {
		$this->logger = $logger;
		$this->TaskService = $TaskService;
		$this->notificationManager = $notificationManager;
		$this->ConfigurationService = $ConfigurationService;
		$this->CorrelationsService = $CorrelationsService;
		$this->HarmonizationThreadService = $HarmonizationThreadService;
		$this->LocalContactsService = $LocalContactsService;
		$this->LocalEventsService = $LocalEventsService;
		$this->LocalTasksService = $LocalTasksService;
		$this->RemoteContactsService = $RemoteContactsService;
		$this->RemoteEventsService = $RemoteEventsService;
		$this->RemoteTasksService = $RemoteTasksService;
		$this->RemoteCommonService = $RemoteCommonService;
		$this->ContactsService = $ContactsService;
		$this->EventsService = $EventsService;
		$this->TasksService = $TasksService;
		$this->LocalContactsStore = $CardDavBackend;
		$this->LocalEventsStore = $CalDavBackend;
		$this->LocalTasksStore = $CalDavBackend;

	}

	/**
	 * Connects to account to verify details, on success saves details to user settings
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid					nextcloud user id
	 * @param string $account_bauth_id		account username
	 * @param string $account_bauth_secret	account secret
	 * 
	 * @return object
	 */
	public function locateAccount(string $account_bauth_id, string $account_bauth_secret): ?object {

		// construct locator
		$locator = new Autodiscover($account_bauth_id, $account_bauth_secret);
		// find configuration
		$result = $locator->discover();

		if ($result > 0) {
			$data = $locator->discovered;

			$o = new \stdClass();
			$o->UserDisplayName = $data['User']['DisplayName'];
			$o->UserEMailAddress = $data['User']['EMailAddress'];
			$o->UserSMTPAddress = $data['User']['AutoDiscoverSMTPAddress'];
			$o->UserSecret = $account_bauth_secret;

			foreach ($data['Account']['Protocol'] as $entry) {
				// evaluate if type is EXCH
				if ($entry['Type'] == 'EXCH') {
					$o->EXCH = new \stdClass();
					$o->EXCH->Server = $entry['Server'];
					$o->EXCH->AD = $entry['AD'];
					$o->EXCH->ASUrl = $entry['ASUrl'];
					$o->EXCH->EwsUrl = $entry['EwsUrl'];
					$o->EXCH->OOFUrl = $entry['OOFUrl'];
				}
				// evaluate if type is IMAP
				elseif ($entry['Type'] == 'IMAP') {
					if ($entry['SSL'] == 'on') {
						$o->IMAPS = new \stdClass();
						$o->IMAPS->Server = $entry['Server'];
						$o->IMAPS->Port = (int) $entry['Port'];
						$o->IMAPS->AuthMode = 'ssl';
						$o->IMAPS->AuthId = $entry['LoginName'];
					} else {
						$o->IMAP = new \stdClass();
						$o->IMAP->Server = $entry['Server'];
						$o->IMAP->Port = (int) $entry['Port'];
						$o->IMAP->AuthMode = 'tls';
						$o->IMAP->AuthId = $entry['LoginName'];
					}
				}
				// evaluate if type is SMTP
				elseif ($entry['Type'] == 'SMTP') {
					if ($entry['SSL'] == 'on') {
						$o->SMTPS = new \stdClass();
						$o->SMTPS->Server = $entry['Server'];
						$o->SMTPS->Port = (int) $entry['Port'];
						$o->SMTPS->AuthMode = 'ssl';
						$o->SMTPS->AuthId = $entry['LoginName'];
					}
					else {
						$o->SMTP = new \stdClass();
						$o->SMTP->Server = $entry['Server'];
						$o->SMTP->Port = (int) $entry['Port'];
						$o->SMTP->AuthMode = 'tls';
						$o->SMTP->AuthId = $entry['LoginName'];
					}
				}
			}

			return $o;

		} else {
			return null;
		}

	}

	/**
	 * Connects to account, verifies details, on success saves details to user settings
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid					nextcloud user id
	 * @param string $account_bauth_id		account username
	 * @param string $account_bauth_secret	account secret
	 * @param string $account_server		FQDN or IP
	 * @param array $flags
	 * 
	 * @return bool
	 */
	public function connectAccountAlternate(string $uid, string $account_bauth_id, string $account_bauth_secret, string $account_server = '', array $flags = []): bool {

		// define place holders
		$connect = false;
		$service_configuration = null;

		// evaluate, if server is empty or connect mail app flag is set
		if (empty($account_server) || in_array('CONNECT_MAIL', $flags)) {
			// locate service information
			$service_configuration = $this->locateAccount($account_bauth_id, $account_bauth_secret);
			// evaluate, if ews server information exists in the located service information
			if (isset($service_configuration->EXCH->Server)) {
				$account_server = $service_configuration->EXCH->Server;
			}
			// evaluate, if account id information exists in the located service information
			if (isset($service_configuration->UserEMailAddress)) {
				$account_id = $service_configuration->UserEMailAddress;
			}
			// evaluate, if account name information exists in the located service information
			if (isset($service_configuration->UserDisplayName)) {
				$account_name = $service_configuration->UserDisplayName;
			}
		}
		// validate server
		if (!\OCA\EWS\Utile\Validator::host($account_server)) {
			return false;
		}
		// validate auth id
		if (!\OCA\EWS\Utile\Validator::username($account_bauth_id)) {
			return false;
		}
		// validate auth secret
		if (empty($account_bauth_secret)) {
			return false;
		}
		// evaluate validate flag
		if (in_array("VALIDATE", $flags)) {
			// construct remote data store client
			$RemoteStore = new EWSClient(
				$account_server, 
				new \OCA\EWS\Components\EWS\AuthenticationBasic($account_bauth_id, $account_bauth_secret), 
				'Exchange2007'
			);
			// retrieve and evaluate transport verification option
			if ($this->ConfigurationService->retrieveSystemValue('transport_verification') == '0') {
				$RemoteStore->configureTransportVerification(false);
			}
			// enable transport body retention
			$RemoteStore->retainTransportResponseBody(true);
			// retrieve root folder attributes
			$rs = $this->RemoteCommonService->fetchFolder($RemoteStore, 'root', true, 'A');
			// evaluate server response
			if (isset($rs)) {
				// extract server version from response body message
				preg_match_all(
					'/<ServerVersionInfo[^>]*?\sVersion=(["\'])?((?:.(?!\1|>))*.?)\1?/',
					$RemoteStore->discloseTransportResponseBody(),
					$match
				);
				// evaluate, if server version was found.
				if ($match[2][0]) {
					$account_protocol = $match[2][0];
				}
				$connect = true;
			}
		}
		else {
			$connect = true;
		}
		// evaluate connect status
		if ($connect) {
			// evaluate if account protocol has been set.
			if (empty(trim($account_protocol))) {
				$account_protocol = 'Exchange2010';
			}
			// evaluate if account id has been set.
			if (empty($account_id)) {
				$account_id = $account_bauth_id;
			}
			// evaluate if account name has been set.
			if (empty($account_name)) {
				$account_name = '';
			}
			// deposit authentication to datastore
			$this->ConfigurationService->depositProvider($uid, ConfigurationService::ProviderAlternate);
			$this->ConfigurationService->depositUserValue($uid, 'account_id', (string) $account_id);
			$this->ConfigurationService->depositUserValue($uid, 'account_name', (string) $account_name);
			$this->ConfigurationService->depositUserValue($uid, 'account_server', (string) $account_server);
			$this->ConfigurationService->depositUserValue($uid, 'account_protocol', (string) $account_protocol);
			$this->ConfigurationService->depositUserValue($uid, 'account_bauth_id', (string) $account_bauth_id);
			$this->ConfigurationService->depositUserValue($uid, 'account_bauth_secret', (string) $account_bauth_secret);
			$this->ConfigurationService->depositUserValue($uid, 'account_connected', 1);
			// register harmonization task
			$this->TaskService->add(\OCA\EWS\Tasks\HarmonizationLauncher::class, ['uid' => $uid]);
		}
		// evaluate, if connect mail flag was set and if auto config was found
		if ($connect && in_array("CONNECT_MAIL", $flags) && isset($service_configuration)) {
			$this->connectMail($uid, $service_configuration);
		}

		if ($connect) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Connects to account, verifies details, on success saves details to user settings
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid				nextcloud user id
	 * @param string $code				authentication code
	 * @param array $flags
	 * 
	 * @return bool
	 */
	public function connectAccountMS365(string $uid, string $code, array $flags): bool {

		$code = rtrim($code,'#');

		try {
			$data = \OCA\EWS\Integration\Microsoft365::createAccess($code);
		} catch (Exception $e) {
			$this->logger->error('Could not link Microsoft account: ' . $e->getMessage(), [
				'exception' => $e,
			]);
			return false;
		}

		if (is_array($data)) {
			// deposit configuration to datastore
			$this->ConfigurationService->depositProvider($uid, ConfigurationService::ProviderMS365);
			$this->ConfigurationService->depositUserValue($uid, 'account_id', (string) $data['email']);
			$this->ConfigurationService->depositUserValue($uid, 'account_name', (string) $data['name']);
			$this->ConfigurationService->depositUserValue($uid, 'account_server', (string) $data['service_server']);
			$this->ConfigurationService->depositUserValue($uid, 'account_protocol', (string) $data['service_protocol']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_access', (string) $data['access']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_expiry', (string) $data['expiry']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_refresh', (string) $data['refresh']);
			$this->ConfigurationService->depositUserValue($uid, 'account_connected', '1');
			// register harmonization task
			$this->TaskService->add(\OCA\EWS\Tasks\HarmonizationLauncher::class, ['uid' => $uid]);

			return true;
		} else {
			return false;
		}

	}

	/**
	 * Reauthorize to account, verifies details, on success saves details to user settings
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid				nextcloud user id
	 * @param string $code				authentication refresh code
	 * 
	 * @return bool
	 */
	public function refreshAccountMS365(string $uid, string $code): bool {

		try {
			$data = \OCA\EWS\Integration\Microsoft365::refreshAccess($code);
		} catch (Exception $e) {
			$this->logger->error('Could not refresh Microsoft account access token: ' . $e->getMessage(), [
				'exception' => $e,
			]);
			return false;
		}

		if (is_array($data)) {
			// deposit authentication to datastore
			$this->ConfigurationService->depositProvider($uid, ConfigurationService::ProviderMS365);
			$this->ConfigurationService->depositUserValue($uid, 'account_id', (string) $data['email']);
			$this->ConfigurationService->depositUserValue($uid, 'account_name', (string) $data['name']);
			$this->ConfigurationService->depositUserValue($uid, 'account_server', (string) $data['service_server']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_access', (string) $data['access']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_expiry', (string) $data['expiry']);
			$this->ConfigurationService->depositUserValue($uid, 'account_oauth_refresh', (string) $data['refresh']);
			$this->ConfigurationService->depositUserValue($uid, 'account_connected', '1');

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
		// delete configuration
		$this->ConfigurationService->destroyUser($uid);

	}

	/**
	 * Connects Mail App
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	nextcloud user id
	 * 
	 * @return void
	 */
	public function connectMail(string $uid, object $configuration): void {

		// evaluate if mail app exists
		if (!$this->ConfigurationService->isMailAppAvailable()) {
			return;
		}
		// evaluate if configuration contains the accounts email address
		if (empty($configuration->UserEMailAddress) && empty($configuration->UserSMTPAddress)) {
			return;
		}
		// evaluate if configuration contains IMAP parameters
		if (!isset($configuration->IMAP) && !isset($configuration->IMAPS)) {
			return;
		}
		// evaluate if configuration contains SMTP parameters
		if (!isset($configuration->SMTP) && !isset($configuration->SMTPS)) {
			return;
		}
		//construct mail account manager 
		$mam = \OC::$server->get(\OCA\Mail\Service\AccountService::class);
		// retrieve configured mail account
		$accounts = $mam->findByUserId($uid);
		// search for existing account that matches
		foreach ($accounts as $entry) {
			if ($configuration->UserEMailAddress == $entry->getEmail() || 
			    $configuration->UserSMTPAddress == $entry->getEmail()) {
				return;
			}
		}

		$account = \OC::$server->get(\OCA\Mail\Db\MailAccount::class);
		$account->setUserId($uid);
		$account->setName($configuration->UserDisplayName);
		$account->setEmail($configuration->UserEMailAddress);
		$account->setAuthMethod('password');

		// evaluate if type is IMAPS is present
		if (isset($configuration->IMAPS)) {
			$imap = $configuration->IMAPS;
		} else{
			$imap = $configuration->IMAP;
		}

		$account->setInboundHost($imap->Server);
		$account->setInboundPort($imap->Port);
		$account->setInboundSslMode($imap->AuthMode);
		$account->setInboundUser($imap->AuthId);
		$account->setInboundPassword($this->ConfigurationService->encrypt($configuration->UserSecret));
		
		// evaluate if type is SMTPS is present
		if (isset($configuration->SMTPS)) {
			$smtp = $configuration->SMTPS;
		} else{
			$smtp = $configuration->SMTP;
		}

		$account->setOutboundHost($smtp->Server);
		$account->setOutboundPort($smtp->Port);
		$account->setOutboundSslMode($smtp->AuthMode);
		$account->setOutboundUser($smtp->AuthId);
		$account->setOutboundPassword($this->ConfigurationService->encrypt($configuration->UserSecret));

		$account = $mam->save($account);

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
		$this->LocalTasksService->DataStore = $this->LocalTasksStore;

		// construct response object
		$response = ['ContactCollections' => [], 'EventCollections' => [], 'TaskCollections' => []];
		// retrieve local collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			$response['ContactCollections'] = $this->LocalContactsService->listCollections($uid);;
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			$response['EventCollections'] = $this->LocalEventsService->listCollections($uid);
		}
		if ($this->ConfigurationService->isTasksAppAvailable()) {
			$response['TaskCollections'] = $this->LocalTasksService->listCollections($uid);
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
		// construct response object
		$response = ['ContactCollections' => [], 'EventCollections' => [], 'TaskCollections' => []];
		// retrieve remote collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			// assign remote data store
			$this->RemoteContactsService->DataStore = $RemoteStore;
			// retrieve remote personal collections
			$response['ContactCollections'] = array_merge($response['ContactCollections'], $this->RemoteContactsService->listCollections('U', 'Personal - '));
			// retrieve remote public collections
			$response['ContactCollections'] = array_merge($response['ContactCollections'], $this->RemoteContactsService->listCollections('P', 'Public - '));
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			// assign remote data store
			$this->RemoteEventsService->DataStore = $RemoteStore;
			// retrieve remote personal collections
			$response['EventCollections'] = array_merge($response['EventCollections'], $this->RemoteEventsService->listCollections('U', 'Personal - '));
			// retrieve remote public collections
			$response['EventCollections'] = array_merge($response['EventCollections'], $this->RemoteEventsService->listCollections('P', 'Public - '));
		}
		if ($this->ConfigurationService->isTasksAppAvailable()) {
			// assign remote data store
			$this->RemoteTasksService->DataStore = $RemoteStore;
			// retrieve remote personal collections
			$response['TaskCollections'] = array_merge($response['TaskCollections'], $this->RemoteTasksService->listCollections('U', 'Personal - '));
			// retrieve remote public collections
			$response['TaskCollections'] = array_merge($response['TaskCollections'], $this->RemoteTasksService->listCollections('P', 'Public - '));
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
		$response = ['ContactCorrelations' => [], 'EventCorrelations' => [], 'TaskCorrelations' => []];
		// retrieve local collections
		if ($this->ConfigurationService->isContactsAppAvailable()) {
			$response['ContactCorrelations'] = $this->CorrelationsService->findByType($uid, 'CC');
		}
		if ($this->ConfigurationService->isCalendarAppAvailable()) {
			$response['EventCorrelations'] = $this->CorrelationsService->findByType($uid, 'EC');
		}
		if ($this->ConfigurationService->isTasksAppAvailable()) {
			$response['TaskCorrelations'] = $this->CorrelationsService->findByType($uid, 'TC');
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
	 * @param array $tc		tasks collection(s) correlations
	 * 
	 * @return array of collection correlation(s) and attributes
	 */
	public function depositCorrelations(string $uid, array $cc, array $ec, array $tc): void {
		
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
		// deposit tasks correlations
		if ($this->ConfigurationService->isTasksAppAvailable()) {
			foreach ($tc as $entry) {
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
								$cc->settype('TC'); // Correlation Type
								$cc->setuid($uid); // User ID
								$cc->setloid($entry['loid']); // Local ID
								$cc->setroid($entry['roid']); // Remote ID
								$this->CorrelationsService->create($cc);
								break;
							case 'U':
								$cc = $this->CorrelationsService->fetch($entry['id']);
								if ($this->UserId == $entry['uid']) {
									$cc->settype('TC'); // Correlation Type
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

		switch ($this->ConfigurationService->retrieveProvider($uid)) {
			case ConfigurationService::ProviderMS365:
				// evaluate, if client does not exists or token is expired
				if (!$this->RemoteStore instanceof EWSClient || $this->RemoteStore->getAuthentication()->Expiry < time()) {
					// retrieve oauth expiry information
					$account_oauth_expiry = (int) $this->ConfigurationService->retrieveUserValue($uid, 'account_oauth_expiry');
					//evaluate if token expired
					if ($account_oauth_expiry < time()) {
						// retrieve refresh token information
						$account_oauth_refresh = $this->ConfigurationService->retrieveUserValue($uid, 'account_oauth_refresh');
						// refresh access token
						$this->refreshAccountMS365($uid, $account_oauth_refresh);
					}
					// retrieve connection information
					$account_server = $this->ConfigurationService->retrieveUserValue($uid, 'account_server');
					$account_protocol = $this->ConfigurationService->retrieveUserValue($uid, 'account_protocol');
					$account_oauth_access = $this->ConfigurationService->retrieveUserValue($uid, 'account_oauth_access');
					$account_oauth_expiry = $this->ConfigurationService->retrieveUserValue($uid, 'account_oauth_expiry');
					// construct remote data store client
					$this->RemoteStore = new EWSClient(
						$account_server, 
						new \OCA\EWS\Components\EWS\AuthenticationBearer($account_oauth_access, $account_oauth_expiry), 
						$account_protocol
					);
					// retrieve and evaluate transport verification option
					if ($this->ConfigurationService->retrieveSystemValue('transport_verification') == '0') {
						$this->RemoteStore->configureTransportVerification(true);
					}
				}
				break;
			case ConfigurationService::ProviderAlternate:
				// evaluate, if client does not exists
				if (!$this->RemoteStore instanceof EWSClient) {
					// retrieve connection information
					$account_server = $this->ConfigurationService->retrieveUserValue($uid, 'account_server');
					$account_protocol = $this->ConfigurationService->retrieveUserValue($uid, 'account_protocol');
					$account_bauth_id = $this->ConfigurationService->retrieveUserValue($uid, 'account_bauth_id');
					$account_bauth_secret = $this->ConfigurationService->retrieveUserValue($uid, 'account_bauth_secret');
					// construct remote data store client
					$this->RemoteStore = new EWSClient(
						$account_server, 
						new \OCA\EWS\Components\EWS\AuthenticationBasic($account_bauth_id, $account_bauth_secret),
						$account_protocol
					);
					// retrieve and evaluate transport verification option
					if ($this->ConfigurationService->retrieveSystemValue('transport_verification') == '0') {
						$this->RemoteStore->configureTransportVerification(true);
					}
				}
				break;
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

		try {
			// retrieve Configuration
			$Configuration = $this->ConfigurationService->retrieveUser($uid);
			$Configuration = $this->ConfigurationService->toUserConfigurationObject($Configuration);
			// create remote store client
			$RemoteStore = $this->createClient($uid);
			// Test Contacts
			$this->ContactsService->RemoteStore = $RemoteStore;
			$result = $this->ContactsService->performTest($action, $Configuration);
			// Test Events
			$this->EventsService->RemoteStore = $RemoteStore;
			$result = $this->EventsService->performTest($action, $Configuration);
			// Test Tasks
			//$this->TasksService->RemoteStore = $RemoteStore;
			//$result = $this->TasksService->performTest($action, $Configuration);
			// destroy remote store client
			$this->destroyClient($RemoteStore);
		} catch (Exception $e) {
			$result = [
					'error' => 'Unknown Test failure:' . $e,
			];
		}
	}
	
}
