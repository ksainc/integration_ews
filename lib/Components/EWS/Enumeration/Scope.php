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
 * Defines the scope of a message tracking report.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class Scope extends Enumeration
{
    /**
     * The message tracking scopes spans across a forest.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FOREST = 'Forest';

    /**
     * The message tracking scopes spans across an organization.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ORGANIZATION = 'Organization';

    /**
     * The message tracking scopes spans across a site.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const SITE = 'Site';
}
