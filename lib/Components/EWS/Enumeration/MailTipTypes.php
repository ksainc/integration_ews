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
 * Defines the types of mail tips requested from the service.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class MailTipTypes extends Enumeration
{
    /**
     * Represents all available mail tips.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * Represents a custom mail tip.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CUSTOM_MAIL_TIP = 'CustomMailTip';

    /**
     * Indicates whether delivery restrictions will prevent the sender's message
     * from reaching the recipient.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DELIVERY_RESTRICTION = 'DeliveryRestriction';

    /**
     * Represents the count of external members.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const EXTERNAL_MEMBER_COUNT = 'ExternalMemberCount';

    /**
     * Indicates whether the recipient is invalid.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_RECIPIENT = 'InvalidRecipient';

    /**
     * Represents the status for a mailbox that is full.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MAILBOX_FULL_STATUS = 'MailboxFullStatus';

    /**
     * Represents the maximum message size a recipient can accept.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MAX_MESSAGE_SIZE = 'MaxMessageSize';

    /**
     * Indicates whether the sender's message will be reviewed by a moderator.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MODERATION_STATUS = 'ModerationStatus';

    /**
     * Represents the Out of Office (OOF) message.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const OUT_OF_OFFICE_MESSAGE = 'OutOfOfficeMessage';

    /**
     * Represents the count of all members.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const TOTAL_MEMBER_COUNT = 'TotalMemberCount';
}
