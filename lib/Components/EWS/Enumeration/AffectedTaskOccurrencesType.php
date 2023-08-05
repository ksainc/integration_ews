<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines whether a task instance or a task master is deleted by a DeleteItem
 * operation.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class AffectedTaskOccurrencesType extends Enumeration
{
    /**
     * A delete item request deletes the master task, and therefore all
     * recurring tasks that are associated with the master task.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ALL = 'AllOccurrences';

    /**
     * A delete item request deletes only specific occurrences of a task.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SPECIFIED = 'SpecifiedOccurrenceOnly';
}
