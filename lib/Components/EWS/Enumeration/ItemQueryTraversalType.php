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
 * Types of sub-tree traversal for deletion and enumeration.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ItemQueryTraversalType extends Enumeration
{
    /**
     * Returns only the identities of associated items in the folder.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ASSOCIATED = 'Associated';

    /**
     * Returns only the identities of items in the folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SHALLOW = 'Shallow';

    /**
     * Returns only the identities of items that are in a folder's dumpster.
     *
     * Note that a soft-deleted traversal combined with a search restriction
     * will result in zero items returned even if there are items that match the
     * search criteria.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SOFT_DELETED = 'SoftDeleted';
}
