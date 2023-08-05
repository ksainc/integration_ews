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
 * Represents values for various types of mail tips.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailTips extends Type
{
    /**
     * Represents a customized mail tip message.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $CustomMailTip;

    /**
     * Indicates whether delivery restrictions will prevent the sender’s message
     * from reaching the recipient.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $DeliveryRestricted;

    /**
     * Represents the count of external members in a group.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $ExternalMemberCount;

    /**
     * Indicates whether the recipient is invalid.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $InvalidRecipient;

    /**
     * Indicates whether the recipient's mailbox is being moderated.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IsModerated;

    /**
     * Indicates whether the mailbox for the recipient is full.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $MailboxFull;

    /**
     * Represents the maximum message size the recipient can accept.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $MaxMessageSize;

    /**
     * Represents the response message and a duration time for sending the
     * response message.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\OutOfOfficeMailTip
     */
    public $OutOfOffice;

    /**
     * Indicates that the mail tips in this element could not be evaluated
     * before the server's processing timeout expired.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailTipTypes
     */
    public $PendingMailTips;

    /**
     * Represents the mailbox of the recipient.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $RecipientAddress;

    /**
     * Represents the count of all members in a group.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $TotalMemberCount;
}
