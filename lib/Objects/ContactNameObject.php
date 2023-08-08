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

class ContactNameObject {

    public ?string $Last;
    public ?string $First;
	public ?string $Other;
    public ?string $Prefix;
    public ?string $Suffix;
    public ?string $PhoneticLast;
    public ?string $PhoneticFirst;
	public ?string $PhoneticOther;
    public ?string $Aliases;
	
	public function __construct(
        ?string $last = null,
        ?string $first = null,
        ?string $other = null,
        ?string $prefix = null,
        ?string $suffix = null,
        ?string $phoneticlast = null,
        ?string $phoneticfirst = null,
        ?string $aliases = null,
    ) {
        $this->Last = $last;
        $this->First = $first;
        $this->Other = $other;
        $this->Prefix = $prefix;
        $this->Suffix = $suffix;
        $this->PhoneticLast = $phoneticlast;
        $this->PhoneticFirst = $phoneticfirst;
        $this->Aliases = $aliases;
	}
}
