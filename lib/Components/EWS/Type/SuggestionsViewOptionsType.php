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

use OCA\EWS\Components\EWS\Type;

/**
 * Defines  the options for obtaining meeting suggestion information.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SuggestionsViewOptionsType extends Type
{
    /**
     * Represents the start time of a meeting that you want to update with the
     * suggested meeting time results.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $CurrentMeetingTime;

    /**
     * Identifies the time span that is queried for detailed information about
     * suggested meeting times.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\Duration
     */
    public $DetailedSuggestionsWindow;

    /**
     * This element is not used.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $GlobalObjectId;

    /**
     * Specifies the percentage of attendees that must have the time period open
     * for the time period to qualify as a good suggested meeting time.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $GoodThreshold;

    /**
     * Specifies the number of suggested results for meeting times outside
     * regular working hours per day.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MaximumNonWorkHourResultsByDay;

    /**
     * Specifies the number of suggested meeting times per day returned in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MaximumResultsByDay;

    /**
     * Specifies the length of the meeting to be suggested.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MeetingDurationInMinutes;

    /**
     * Specifies the quality of meeting suggestions to be returned in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SuggestionQuality
     */
    public $MinimumSuggestionQuality;
}
