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
 * Specifies the flag for action value that must appear on incoming messages in
 * order for the condition or exception to apply.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class FlaggedForActionType extends Enumeration
{
    /**
     * Indicates that the message may contain any flag.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const ANY = 'Any';

    /**
     * Indicates that the message has been flagged for a call.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CALL = 'Call';

    /**
     * Indicates that the message is not to be forwarded.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DO_NOT_FORWARD = 'DoNotForward';

    /**
     * Indicates that the message has been flagged for a follow up.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FOLLOW_UP = 'FollowUp';

    /**
     * Indicates that the message is to be forwarded.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FORWARD = 'Forward';

    /**
     * Indicates that the message has been flagged as FYI.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FYI = 'FYI';

    /**
     * Indicates that the message does not require a response.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const NO_RESPONSE_NECESSARY = 'NoResponseNecessary';

    /**
     * Indicates that the message is to be read.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const READ = 'Read';

    /**
     * Indicates that the message needs a reply.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const REPLY = 'Reply';

    /**
     * Indicates that the message needs a reply to all recipients.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const REPLY_TO_ALL = 'ReplyToAll';

    /**
     * Indicates that the message has been flagged for review.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const REVIEW = 'Review';
}
