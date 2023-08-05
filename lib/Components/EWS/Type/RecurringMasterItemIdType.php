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

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines a  recurrence master item by identifying the identifiers of one of
 * its related occurrence items.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RecurringMasterItemIdType extends BaseItemIdType
{
    /**
     * Identifies a specific version of a single occurrence of a recurring
     * master item.
     *
     * Additionally, the recurring master item is also identified because it and
     * the single occurrence will contain the same change key.
     *
     * This attribute is optional.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ChangeKey;

    /**
     * Identifies a single occurrence of a recurring master item.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $OccurrenceId;
}
