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
 * Represents a single message result for a FindMessageTrackingReportResponse
 * element.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FindMessageTrackingSearchResultType extends Type
{
    /**
     * Contains the name of the server in the forest that first accepted the
     * message.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $FirstHopServer;

    /**
     * Contains an internal ID that identifies the message in the transport
     * database.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $MessageTrackingReportId;

    /**
     * Contains the name of the server in the forest that previously accepted
     * the message.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $PreviousHopServer;

    /**
     * Contains a list of one or more tracking properties.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;

    /**
     * Contains contact information for the alleged sender of an e-mail message.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $PurportedSender;

    /**
     * Contains a list of e-mail addresses that received this message.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $Recipients;

    /**
     * Contains the e-mail message sender’s address.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Sender;

    /**
     * Contains the e-mail message subject.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $Subject;

    /**
     * Contains the time that the message was submitted.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $SubmittedTime;
}
