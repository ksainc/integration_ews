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

namespace OCA\EWS\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class CorrelationMapper extends QBMapper {

	private IDBConnection $DataStore;
	private string $DataStoreTable = 'ews_integration_crls';

	public function __construct(IDBConnection $db) {
		parent::__construct($db, $this->DataStoreTable, Correlation::class);
	}

	/**
	 * retrieve correlations from data store by user id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * 
	 * @return array of Correlation objects
	 */
	public function findByUserId(string $uid): array {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)));
		// execute command and return results
		return $this->findEntities($qb);
	}

	/**
	 * retrieve correlations from data store by correlation type
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $type	correlation type - CC / CO / EC / EO
	 * 
	 * @return array of Correlation objects
	 */
	public function findByType(string $uid, string $type): array {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('type', $qb->createNamedParameter($type)));
		// execute command and return results
		return $this->findEntities($qb);

	}

	/**
	 * retrieve correlation from data store by local object id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $loid	local object id
	 * @param string $lcid	local collection id
	 * 
	 * @return Correlation
	 */
	public function findByLocalId(string $uid, string $type, string $loid, string $lcid = null): Correlation {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		if ($lcid) {
			$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('type', $qb->createNamedParameter($type)))
			->andWhere($qb->expr()->eq('loid', $qb->createNamedParameter($loid)))
			->andWhere($qb->expr()->eq('lcid', $qb->createNamedParameter($lcid)));
		}
		else {
			$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('type', $qb->createNamedParameter($type)))
			->andWhere($qb->expr()->eq('loid', $qb->createNamedParameter($loid)));
		}
		// execute command and return results
		return $this->findEntity($qb);
	}

	/**
	 * retrieve correlation from data store by remote object id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $roid	remote object id
	 * @param string $rcid	remote collection id
	 * 
	 * @return Correlation
	 */
	public function findByRemoteId(string $uid, string $type, string $roid, string $rcid = null): Correlation {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		if ($rcid) {
			$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('type', $qb->createNamedParameter($type)))
			->andWhere($qb->expr()->eq('roid', $qb->createNamedParameter($roid)))
			->andWhere($qb->expr()->eq('rcid', $qb->createNamedParameter($rcid)));
		} else {
			$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('type', $qb->createNamedParameter($type)))
			->andWhere($qb->expr()->eq('roid', $qb->createNamedParameter($roid)));
		}
		// execute command and return results
		return $this->findEntity($qb);
	}

	/**
	 * retrieve correlations from data store
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $loid	local object id
	 * @param string $roid	remote object id
	 * 
	 * @return Correlation
	 */
	public function find(string $uid, string $loid, string $roid): Correlation {

		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('loid', $qb->createNamedParameter($loid)))
			->andWhere($qb->expr()->eq('roid', $qb->createNamedParameter($roid)));
		// execute command and return results
		return $this->findEntity($qb);

	}

	/**
	 * retrieve correlation from data store
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $id	correlation id
	 * 
	 * @return Correlation
	 */
	public function fetch(string $id): Correlation {

		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('id', $qb->createNamedParameter($id)));
		// execute command and return result
		return $this->findEntity($qb);
	}

	/**
	 * delete correlations from data store by user id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * 
	 * @return mixed
	 */
	public function deleteByUserId(string $uid): mixed {

		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->delete($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)));
		// execute command and return result
		return $qb->execute();

	}

	/**
	 * delete correlations from data store by affiliation id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $aid	affiliation id
	 * 
	 * @return mixed
	 */
	public function deleteByAffiliationId(string $uid, string $aid): mixed {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->delete($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('aid', $qb->createNamedParameter($aid)));
		// execute command and return result
		return $qb->execute();

	}

	/**
	 * delete correlations from data store by local and remote collection id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $lcid	local collection id
	 * @param string $rcid	remote collection id
	 * 
	 * @return mixed
	 */
	public function deleteByUserIdAndCollectionId(string $uid, string $lcid, string $rcid): mixed {
		
		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->delete($this->DataStoreTable)
			->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid)))
			->andWhere($qb->expr()->eq('lcid', $qb->createNamedParameter($lcid)))
			->andWhere($qb->expr()->eq('rcid', $qb->createNamedParameter($rcid)));
		// execute command and return result
		return $qb->execute();

	}
}
