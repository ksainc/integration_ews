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
 * Defines an item preview for a discovery search.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchPreviewItemType extends Type
{
    /**
     * Represents a collection of recipients to receive a blind carbon copy
     * (Bcc) of an e-mail message.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $BccRecipients;

    /**
     * Represents a collection of recipients that will receive a copy of the
     * message.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRecipientsType
     */
    public $CcRecipients;

    /**
     * Specifies the time at which the item was created.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $CreatedTime;

    /**
     * Specifies an array of additional properties.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfExtendedPropertyType
     */
    public $ExtendedProperties;

    /**
     * Specifies whether the item has attachments.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $HasAttachment;

    /**
     * Specifies the identifier of the item.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $Id;

    /**
     * Describes the importance of an item or the aggregated importance of all
     * items in a conversation in the current folder.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ImportanceChoicesType
     */
    public $Importance;

    /**
     * Represents the message class of an item.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ItemClassType
     */
    public $ItemClass;

    /**
     * Contains the mailbox identifier and the user’s primary Simple Mail
     * Transfer Protocol (SMTP) address.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PreviewItemMailboxType
     */
    public $Mailbox;

    /**
     * Specifies the link to preview an item in Microsoft Outlook Web App.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $OwaLink;

    /**
     * Specifies the identifier of the parent item in a search preview.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ParentId;

    /**
     * Specifies the first 256 characters of an item for display.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Preview;

    /**
     * Indicates whether a client can read a folder or item.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $Read;

    /**
     * Specifies the time at which an item was received.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ReceivedTime;

    /**
     * Specifies the e-mail address of the person who sent an item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Sender;

    /**
     * Specifies the time at which the item was sent.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $SentTime;

    /**
     * Specifies the total size of one or more mailbox items.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $Size;

    /**
     * Specifies a value used for sorting.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SortValue;

    /**
     * Represents the subject property of Exchange store items.
     *
     * The subject is limited to 255 characters.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Subject;

    /**
     * Specifies a list of recipients to whom the item was sent.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSmtpAddressType
     */
    public $ToRecipients;

    /**
     * Specifies a unique hash value used for de-duplication.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $UniqueHash;
}
