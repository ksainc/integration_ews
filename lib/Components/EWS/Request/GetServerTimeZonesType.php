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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to retrieve time zone definitions from the Exchange
 * server.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetServerTimeZonesType extends BaseRequestType
{
    /**
     * Contains an array of time zone definition identifiers that specifies the
     * requested time zone definitions.
     *
     * This element is optional.
     *
     * If this element is not included in the GetServerTimeZones operation
     * request, all time zone definitions that are available on the server are
     * returned in the response.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfTimeZoneIdType
     */
    public $Ids;

    /**
     * Specifies whether the GetServerTimeZones operation returns the complete
     * definition or only the name and identifier for each time zone.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $ReturnFullTimeZoneData = true;
}
