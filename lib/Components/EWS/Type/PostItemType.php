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

/**
 * Represents a post item in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PostItemType extends ItemType
{
    /**
     * Contains a binary ID that represents the thread to which this message
     * belongs.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $ConversationIndex;

    /**
     * Represents the conversation identifier.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ConversationTopic;

    /**
     * Represents the address from which the post item was sent.
     *
     * The From element can only be set at creation time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SingleRecipientType
     */
    public $From;

    /**
     * Represents the Internet message identifier of an item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $InternetMessageId;

    /**
     * Indicates whether a message has been read.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsRead;

    /**
     * Represents the time that a PostItem was posted.
     *
     * This element is read-only.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $PostedTime;

    /**
     * Represents the Usenet header that is used to associate replies with the
     * original messages.
     *
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $References;

    /**
     * Identifies the sender of an item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SingleRecipientType
     */
    public $Sender;
}
