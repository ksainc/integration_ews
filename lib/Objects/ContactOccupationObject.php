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

namespace OCA\EWS\Objects;

class ContactOccupationObject {

    public ?string $Organization;
	public ?string $Title;
    public ?string $Role;
    public ?string $Department = null;
    public ?string $Location = null;
    public ?string $Logo = null;
    
    public function __construct(?string $organization = null, ?string $title = null, ?string $role = null) {
        $this->Organization = $organization;
        $this->Title = $title;
        $this->Role = $role;
	}
}
