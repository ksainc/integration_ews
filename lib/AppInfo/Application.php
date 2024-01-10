<?php
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

declare(strict_types=1);

namespace OCA\EWS\AppInfo;

use OCP\IConfig;
use OCP\App\IAppManager;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCA\DAV\Events\AddressBookDeletedEvent;
use OCA\DAV\Events\CalendarDeletedEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Notification\IManager as INotificationManager;
use OCP\User\Events\UserDeletedEvent;

use OCA\EWS\Events\AddressBookDeletedListener;
use OCA\EWS\Events\CalendarDeletedListener;
use OCA\EWS\Events\UserDeletedListener;
use OCA\EWS\Notification\Notifier;

/**
 * Class Application
 *
 * @package OCA\EWS\AppInfo
 */
class Application extends App implements IBootstrap {
    // assign application identification
    public const APP_ID = 'integration_ews';

    public function __construct(array $urlParams = []) {
        parent::__construct(self::APP_ID, $urlParams);

        // retrieve harmonization mode
        $mode = \OC::$server->getConfig()->getAppValue(Application::APP_ID, 'harmonization_mode');
        $appmanager = $this->getContainer()->get(IAppManager::class);
        $contacts = $appmanager->isInstalled('contacts');
        $calendar = $appmanager->isInstalled('calendar');

        // register notifications
        $manager = $this->getContainer()->get(INotificationManager::class);
        $manager->registerNotifierService(Notifier::class);
        // register event handlers
        $dispatcher = $this->getContainer()->get(IEventDispatcher::class);
        $dispatcher->addServiceListener(UserDeletedEvent::class, UserDeletedListener::class);

        if ($contacts == true) {
            $dispatcher->addServiceListener(AddressBookDeletedEvent::class, AddressBookDeletedListener::class);
        }
        
        if ($calendar == true) {
            $dispatcher->addServiceListener(CalendarDeletedEvent::class, CalendarDeletedListener::class);
        }
    }

    public function register(IRegistrationContext $context): void {
    }

    public function boot(IBootContext $context): void {
    }
}
