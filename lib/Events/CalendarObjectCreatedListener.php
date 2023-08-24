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

namespace OCA\EWS\Events;

use Psr\Log\LoggerInterface;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCA\DAV\Events\CalendarObjectCreatedEvent;

use OCA\EWS\Db\Action;
use OCA\EWS\Db\ActionMapper;
use OCA\EWS\Db\Correlation;
use OCA\EWS\Service\CorrelationsService;

class CalendarObjectCreatedListener implements IEventListener {
    /**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct(LoggerInterface $logger, ActionMapper $ActionManager, CorrelationsService $CorrelationsService) {
		$this->logger = $logger;
		$this->ActionManager = $ActionManager;
		$this->CorrelationsService = $CorrelationsService;
	}

    public function handle(Event $event): void {

        if ($event instanceof CalendarObjectCreatedEvent) {
			try {
				// retrieve collection and object attributes
				$ec = $event->getCalendarData();
				$eo = $event->getObjectData();
				// evaluate object type
				if (strtoupper($eo['component']) == 'VEVENT') {
					$ccs = 'EC';
					$cos = 'EO';
				}
				elseif (strtoupper($eo['component']) == 'VTODO') {
					$ccs = 'TC';
					$cos = 'TO';
				}
				// evaluate if collection and object selector is populated
				if (isset($ccs) && isset($cos)) {
					// determine ids and state  
					$uid = str_replace('principals/users/', '', $ec['principaluri']);
					$cid = (string) $ec['id'];
					$oid = str_replace('-deleted', '', $eo['uri']);
					$ostate = trim($eo['etag'],'"');
					// retrieve collection correlation
					$cc = $this->CorrelationsService->findByLocalId($uid, $ccs, $cid);
					// evaluate correlation, if correlation exists for the local collection create action
					if ($cc instanceof \OCA\EWS\Db\Correlation) {
						// retrieve object correlation
						$ci = $this->CorrelationsService->findByLocalId($uid, $cos, $oid, $cid);
						// evaluate correlation, if dose not exists or state does not match, create action
						// work around to filter out harmonization generated events
						if (!($ci instanceof \OCA\EWS\Db\Correlation) || $ci->getlostate() != $ostate) {
							// construct action entry
							$a = new Action();
							$a->setuid($uid);
							$a->settype($cos);
							$a->setaction('C');
							$a->setorigin('L');
							$a->setlcid($cid);
							$a->setloid($oid);
							$a->setlostate($ostate);
							$a->setcreatedon(date(DATE_W3C));
							// deposit action entry
							$this->ActionManager->insert($a);
						}
					}
				}	
			} catch (Exception $e) {
				$this->logger->warning($e->getMessage(), ['uid' => $event->getUser()->getUID()]);
			}
		}
		
    }
}