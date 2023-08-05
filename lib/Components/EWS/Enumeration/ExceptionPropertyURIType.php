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
 * Defines a property of an occurrence of a recurring item.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ExceptionPropertyURIType extends Enumeration
{
    /**
     * Identifies the content as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ATTACHMENT_CONTENT = 'attachment:Content';

    /**
     * Identifies the content type as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ATTACHMENT_CONTENT_TYPE = 'attachment:ContentType';

    /**
     * Identifies the attachment name as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ATTACHMENT_NAME = 'attachment:Name';

    /**
     * Identifies the DayOfMonth as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_DAY_OF_MONTH = 'recurrence:DayOfMonth';

    /**
     * Identifies the day of week index as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_DAY_OF_WEEK_INDEX = 'recurrence:DayOfWeekIndex';

    /**
     * Identifies the DaysOfWeek property as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_DAYS_OF_WEEK = 'recurrence:DaysOfWeek';

    /**
     * Identifies the interval as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_INTERVAL = 'recurrence:Interval';

    /**
     * Identifies the month field as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_MONTH = 'recurrence:Month';

    /**
     * Identifies the number of occurrences as containing an error.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRENCE_NUMBER_OF_OCCURRENCES = 'recurrence:NumberOfOccurrences';
}
