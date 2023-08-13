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
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class TasksUtile {

	private IDBConnection $DataStore;
	private string $DataStoreTable = 'calendarobjects';

	public function __construct(IDBConnection $db) {
		$this->DataStore = $db;
	}

	/**
	 * retrieve task attributes from data store by uuid
	 * 
	 * @since Release 1.0.0
	 * 
	 * @param string $cid	collection id
	 * @param string $uuid	universal unique id
	 * 
	 * @return array of data fields
	 */
	public function findByUUID(string $cid, string $uuid, string $type): ?array {
		
		// construct search command
		$sc = $this->DataStore->getQueryBuilder();
		$sc->select('*')
			->from($this->DataStoreTable)
			->where($sc->expr()->eq('calendarid', $sc->createNamedParameter($cid)))
			->andWhere($sc->expr()->eq('uid', $sc->createNamedParameter($uuid)))
			->andWhere($sc->expr()->eq('componenttype', $sc->createNamedParameter($type)))
			->andWhere($sc->expr()->isNull('deleted_at'));
		// execute command
		$rs = $sc->executeQuery()->fetchAll();
		$sc->executeQuery()->closeCursor();
		// return result or null
		if (is_array($rs) && count($rs) > 0) {
			return $rs;
		}
		else {
			return null;
		}
		
	}

}
