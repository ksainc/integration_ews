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
 * Represents the type of report to get.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MessageTrackingReportTemplateType extends Enumeration
{
    /**
     * Specifies that for a single recipient, the report will display a full
     * history of the events that occurred.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const RECIPIENT_PATH = 'RecipientPath';

    /**
     * Specifies that the report will display all the recipients of the message
     * and the delivery status of the message to each recipient.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const SUMMARY = 'Summary';
}
