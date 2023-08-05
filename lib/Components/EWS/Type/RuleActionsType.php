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
 * Represents the set of actions that are available to be taken on a message
 * when conditions are fulfilled.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RuleActionsType extends Type
{
    /**
     * Represents the categories that are stamped on e-mail messages.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $AssignCategories;

    /**
     * Identifies the ID of the folder that e-mail items will be copied to.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $CopyToFolder;

    /**
     * Indicates whether messages are to be moved to the Deleted Items folder.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $Delete;

    /**
     * Indicates the e-mail addresses to which messages are to be forwarded as
     * attachments.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $ForwardAsAttachmentToRecipients;

    /**
     * Indicates the e-mail addresses to which messages are to be forwarded.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $ForwardToRecipients;

    /**
     * Specifies the importance that is to be stamped on messages.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ImportanceChoicesType
     */
    public $MarkImportance;

    /**
     * Indicates whether messages are to be marked as read.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $MarkAsRead;

    /**
     * Identifies the ID of the folder that e-mail items will be moved to.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $MoveToFolder;

    /**
     * Indicates whether messages are to be permanently deleted and not saved to
     * the Deleted Items folder.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $PermanentDelete;

    /**
     * Indicates the e-mail addresses to which messages are to be redirected.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $RedirectToRecipients;

    /**
     * Indicates the mobile phone numbers to which a Short Message Service (SMS)
     * alert is to be sent.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfEmailAddressesType
     */
    public $SendSMSAlertToRecipients;

    /**
     * Indicates the ID of the template message that is to be sent as a reply to
     * incoming messages.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ServerReplyWithMessage;

    /**
     * Indicates whether subsequent rules are to be evaluated.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $StopProcessingRules;
}
