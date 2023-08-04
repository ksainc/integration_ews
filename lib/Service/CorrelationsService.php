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

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\EWS\Db\Correlation;
use OCA\EWS\Db\CorrelationMapper;

class CorrelationsService {

	const ContactCollection = 'CC';
	const ContactObject = 'CO';
	const EventCollection = 'EC';
	const EventObject = 'EO';

	private CorrelationMapper $mapper;

	public function __construct(CorrelationMapper $mapper) {
		$this->mapper = $mapper;
	}

	private function handleException(Exception $e) {

		throw $e;

	}

	public function findByUserId(string $uid): array {
		
		try {
			return $this->mapper->findByUserId($uid);
		} catch (DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function findByType(string $uid, string $type): array {
		
		try {
			return $this->mapper->findByType($uid, $type);
		} catch (DoesNotExistException $e) {
			return null;
		}

	}

	public function findByLocalId(string $uid, string $type, string $loid, string $lcid = null): ?Correlation {

		try {
			return $this->mapper->findByLocalId($uid, $type, $loid, $lcid);
		} catch (DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function findByRemoteId(string $uid, string $type, string $roid, string $rcid = null): ?Correlation {

		try {
			return $this->mapper->findByRemoteId($uid, $type, $roid, $rcid);
		} catch (DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function find(string $uid, string $loid, string $roid, string $pid = null): ?Correlation {

		try {
			return $this->mapper->find($uid, $loid, $roid, $pid);
		} catch (DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function fetch(string $id): Correlation {

		try {
			return $this->mapper->fetch($id);
		} catch (DoesNotExistException $e) {
			return null;
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function create(Correlation $correlation): Correlation {

		try {
			return $this->mapper->insert($correlation);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function update(Correlation $correlation): Correlation {

		try {
			return $this->mapper->update($correlation);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function delete(Correlation $correlation): mixed {

		try {
			return $this->mapper->delete($correlation);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function deleteByUserId(string $uid): mixed {

		try {
			return $this->mapper->deleteByUserId($uid);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function deleteByAffiliationId(string $uid, string $aid): mixed {

		try {
			return $this->mapper->deleteByAffiliationId($uid, $aid);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	public function deleteByCollectionId(string $uid, string $lpid, string $rpid): mixed {

		try {
			return $this->mapper->deleteByUserIdAndCollectionId($uid, $lpid, $rpid);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}
}
