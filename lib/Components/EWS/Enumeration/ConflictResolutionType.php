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
 * Defines the type of conflict resolution to try during an update.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConflictResolutionType extends Enumeration
{
    /**
     * If there is a conflict, the update operation will overwrite information.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ALWAYS_OVERWRITE = 'AlwaysOverwrite';

    /**
     * The update operation automatically resolves any conflict.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const AUTO_RESOLVE = 'AutoResolve';

    /**
     * If there is a conflict, the update operation fails and an error is
     * returned.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NEVER_OVERWRITE = 'NeverOverwrite';
}
