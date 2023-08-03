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

class ActionMapper extends QBMapper {

	private IDBConnection $DataStore;
	private string $DataStoreTable = 'ews_integration_acts';

	public function __construct(IDBConnection $db) {
		parent::__construct($db, $this->DataStoreTable, Action::class);
	}

	/**
	 * retrieve actions from data store by user id
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * 
	 * @return array of Action objects
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
	 * retrieve actions from data store by action type
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $uid	user id
	 * @param string $type	action type
	 * 
	 * @return array of Action objects
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
	 * retrieve action from data store
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $id	correlation id
	 * 
	 * @return Action
	 */
	public function fetch(string $id): Action {

		// construct data store command
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->DataStoreTable)
			->where($qb->expr()->eq('id', $qb->createNamedParameter($id)));
		// execute command and return result
		return $this->findEntity($qb);
	}

	/**
	 * delete actions from data store by user id
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
}
