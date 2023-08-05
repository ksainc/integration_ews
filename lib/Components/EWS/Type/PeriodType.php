<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
* @author James I. Armes http://jamesarmes.com/
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

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the name, time offset, and unique identifier for a specific stage of
 * the time zone.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PeriodType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Name = null, string $Bias = null, string $Id = null)
    {
        $this->Name = $Name;
        $this->Bias = $Bias;
        $this->Id = $Id;
    }

    /**
     * An xs:duration value that represents the time offset from Coordinated
     * Universal Time (UTC) for the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Bias;

    /**
     * A string value that represents the identifier for the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Id;

    /**
     * A string value that represents the descriptive name of the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;
}
