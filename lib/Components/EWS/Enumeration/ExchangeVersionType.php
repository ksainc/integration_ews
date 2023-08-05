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
 * Defines the versioning information that identifies the schema version to
 * target for a request.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ExchangeVersionType extends Enumeration
{
    /**
     * Target the schema files for the initial release version of Exchange 2007.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const EXCHANGE_2007 = 'Exchange2007';

    /**
     * Target the schema files for Exchange 2007 Service Pack 1 (SP1), Exchange
     * 2007 Service Pack 2 (SP2), and Exchange 2007 Service Pack 3 (SP3).
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const EXCHANGE_2007_SP1 = 'Exchange2007_SP1';

    /**
     * Microsoft Exchange 2007 SP2
     *
     * @var string
     */
    const VERSION_2009 = 'Exchange2009';

    /**
     * Target the schema files for Exchange 2010.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const EXCHANGE_2010 = 'Exchange2010';

    /**
     * Target the schema files for Exchange 2010 Service Pack 1 (SP1).
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    const EXCHANGE_2010_SP1 = 'Exchange2010_SP1';

    /**
     * Target the schema files for Exchange 2010 Service Pack 2 (SP2) and
     * Exchange 2010 Service Pack 3 (SP3).
     *
     * @since Exchange 2010 SP2
     *
     * @var string
     */
    const EXCHANGE_2010_SP2 = 'Exchange2010_SP2';

    /**
     * Target the schema files for Exchange 2013.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const EXCHANGE_2013 = 'Exchange2013';

    /**
     * Target the schema files for Exchange 2013 Service Pack 1 (SP1).
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    const EXCHANGE_2013_SP1 = 'Exchange2013_SP1';

    /**
     * Target the schema files for Exchange 2016.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    const VERSION_2016 = 'Exchange2016';
}
