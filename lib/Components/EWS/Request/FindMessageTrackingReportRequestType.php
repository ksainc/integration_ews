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
 * Represents criteria for the types of messages to find.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class FindMessageTrackingReportRequestType extends BaseRequestType
{
    /**
     * Represents the level of detail for diagnostic reports.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $DiagnosticsLevel;

    /**
     * Contains the name of the domain where the message tracking is executed.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Domain;

    /**
     * Contains the ending date and time for the search.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $EndDateTime;

    /**
     * Contains the name of the mailbox where the cross-premise message was
     * sent.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $FederatedDeliveryMailbox;

    /**
     * Contains the message identifier for the search.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $MessageId;

    /**
     * Contains a list of one or more tracking properties.
     *
     * This element is optional.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;

    /**
     * Contains contact information for the alleged sender of an e-mail message.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $PurportedSender;

    /**
     * Contains the e-mail address for the recipient of the message.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Recipient;

    /**
     * Represents how extensive the message tracking report should be.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\Scope
     */
    public $Scope;

    /**
     * Contains contact information for the sender of the e-mail message.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Sender;

    /**
     * Represents the starting point for tracking a message in a remote site or
     * forest.
     *
     * @since Exchange 2010 SP1
     *
     * @var string
     */
    public $ServerHint;

    /**
     * Contains the starting date and time for the search.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $StartDateTime;

    /**
     * Contains the subject of the e-mail message.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Subject;
}
