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

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

/**
 * @method getid(): int
 * @method getuid(): string
 * @method setuid(string $uid): void
 * @method gettype(): string
 * @method settype(string $type): void
 * @method getaction(): string
 * @method setaction(string $action): void
 * @method getorigin(): string
 * @method setorigin(string $origin): void
 * @method getlcid(): string
 * @method setlcid(string $lcid): void
 * @method getloid(): string
 * @method setloid(string $loid): void
 * @method getlostate(): string
 * @method setlostate(string $lostate): void
 * @method getrcid(): string
 * @method setrcid(string $rcid): void
 * @method getroid(): string
 * @method setroid(string $roid): void
 * @method getrostate(): string
 * @method setrostate(string $rostate): void
 * @method getcreatedon(): string
 * @method setcreatedon(string $createdon): void
 */
class Action extends Entity implements JsonSerializable {
	protected string $uid = '';
	protected string $type = '';
	protected string $action = '';
	protected string $origin = '';
	protected ?string $loid = null;
	protected ?string $lcid = null;
	protected ?string $lostate = null;
	protected ?string $roid = null;
	protected ?string $rcid = null;
	protected ?string $rostate = null;
	protected ?string $createdon = null;

	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'type' => $this->type,
			'action' => $this->action,
			'origin' => $this->origin,
			'lcid' => $this->lcid,
			'loid' => $this->loid,
			'lostate' => $this->lostate,
			'rcid' => $this->rcid,
			'roid' => $this->roid,
			'rostate' => $this->rostate,
			'createdon' => $this->createdon
		];
	}
}
