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
 * @method getaid(): string
 * @method setaid(string $aid): void
 * @method getloid(): string
 * @method setloid(string $loid): void
 * @method getlstate(): string
 * @method setlstate(string $token): void
 * @method getlcid(): string
 * @method setlcid(string $lcid): void
 * @method getroid(): string
 * @method setroid(string $roid): void
 * @method getrstate(): string
 * @method setrstate(string $token): void
 * @method getrcid(): string
 * @method setrcid(string $rcid): void
 */
class Correlation extends Entity implements JsonSerializable {
	protected string $uid = '';
	protected string $type = '';
	protected ?int $aid = null;
	protected string $lid = '';
	protected ?string $lpid = null;
	protected string $loid = '';
	protected ?string $lcid = null;
	protected ?string $lstate = null;
	protected string $rid = '';
	protected ?string $rpid = null;
	protected string $roid = '';
	protected ?string $rcid = null;
	protected ?string $rstate = null;
		
	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'type' => $this->type,
			'aid' => $this->aid,
			'loid' => $this->loid,
			'lcid' => $this->lcid,
			'lstate' => $this->lstate,
			'roid' => $this->roid,
			'rcid' => $this->rcid,
			'rstate' => $this->rstate,
			
		];
	}
}
