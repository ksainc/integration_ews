<?php
declare(strict_types=1);

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

namespace OCA\EWS\Tasks;

use Psr\Log\LoggerInterface;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;

use OCA\EWS\Service\HarmonizationService;
use OCA\EWS\Service\HarmonizationThreadService;

class HarmonizationLauncher extends TimedJob {
    /**
	 * @var LoggerInterface
	 */
	private $logger;
    /**
	 * @var HarmonizationService
	 */
	private $HarmonizationService;
    /**
	 * @var HarmonizationThreadService
	 */
	private $HarmonizationThreadService;

    public function __construct(
        ITimeFactory $time, 
        LoggerInterface $logger, 
        HarmonizationService $HarmonizationService,
        HarmonizationThreadService $HarmonizationThreadService
    ) {
        parent::__construct($time);
        $this->logger = $logger;
        $this->HarmonizationService = $HarmonizationService;
        $this->HarmonizationThreadService = $HarmonizationThreadService;

        // Run every 5min
        $this->setInterval(300);
    }

    protected function run($arguments) {
        // extract user id
        $uid = $arguments['uid'];
        // evaluate harmonization mode
        // active mode
        if ($this->HarmonizationService->getMode() == 'A') {
            // retrieve thread id
            $tid = $this->HarmonizationThreadService->getId($uid);
            // evaluate if thread is live and launch new thred if needed
            if (!$this->HarmonizationThreadService->isActive($uid, $tid)) {
                // launch new thread
                $tid = $this->HarmonizationThreadService->launch($uid);
            }

            if ($tid > 0) {
                $this->HarmonizationThreadService->setId($uid, $tid);
                $this->HarmonizationThreadService->setHeartBeat($uid, time());
            }
        }
        // passive mode
        else {
            $this->HarmonizationService->performHarmonization($uid);
        } 
    }
}