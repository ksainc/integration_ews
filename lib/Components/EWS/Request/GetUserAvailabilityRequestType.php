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
 * Defines the arguments used to obtain user availability information.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetUserAvailabilityRequestType extends BaseRequestType
{
    /**
     * Specifies the type of free/busy information returned in the response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FreeBusyViewOptionsType
     */
    public $FreeBusyViewOptions;

    /**
     * Contains a list of mailboxes to query for availability information.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfMailboxData
     */
    public $MailboxDataArray;

    /**
     * Contains the options for obtaining meeting suggestion information.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SuggestionsViewOptionsType
     */
    public $SuggestionsViewOptions;

    /**
     * Contains elements that identify time zone information.
     *
     * This element also contains information about the transition between
     * standard time and daylight saving time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SerializableTimeZone
     */
    public $TimeZone;
}
