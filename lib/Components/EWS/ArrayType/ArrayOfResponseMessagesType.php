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

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Represents the response messages for an Exchange Web Services request.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class ArrayOfResponseMessagesType extends ArrayType
{
    /**
     * Contains the status and results of an ApplyConversationAction Operation
     * request.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Response\ApplyConversationActionResponseMessageType[]
     */
    public $ApplyConversationActionResponseMessage = array();

    /**
     * Contains the status and result of a single ArchiveItem request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType[]
     */
    public $ArchiveItemResponseMessage = array();

    /**
     * Contains the status and result of a ConvertId request.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Response\ConvertIdResponseMessageType[]
     */
    public $ConvertIdResponseMessage = array();

    /**
     * Contains the status and result of a single CopyFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $CopyFolderResponseMessage = array();

    /**
     * Contains the status and result of a single CopyItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType[]
     */
    public $CopyItemResponseMessage = array();

    /**
     * Contains the status and result of a single CreateAttachment request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\AttachmentInfoResponseMessageType[]
     */
    public $CreateAttachmentResponseMessage = array();

    /**
     * Contains the status and result of a single CreateFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $CreateFolderResponseMessage = array();

    /**
     * Contains the status and result of a single CreateItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType[]
     */
    public $CreateItemResponseMessage = array();

    /**
     * Contains the status and result of a single CreateManagedFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $CreateManagedFolderResponseMessage = array();

    /**
     * Contains the status and results of a CreateUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $CreateUserConfigurationResponseMessage = array();

    /**
     * Contains the status and result of a single DeleteAttachment request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\DeleteAttachmentResponseMessageType[]
     */
    public $DeleteAttachmentResponseMessage = array();

    /**
     * Contains the status and result of a single DeleteFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $DeleteFolderResponseMessage = array();

    /**
     * Contains the status and result of a single DeleteItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $DeleteItemResponseMessage = array();

    /**
     * Contains the status and results of a DeleteUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $DeleteUserConfigurationResponseMessage = array();

    /**
     * Contains the status and result of a single EmptyFolder request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $EmptyFolderResponseMessage = array();

    /**
     * Contains the status and result of a single ExpandDL request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ExpandDLResponseMessageType[]
     */
    public $ExpandDLResponseMessage = array();

    /**
     * Contains the status and results of a single ExportItems request.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Response\ExportItemsResponseMessageType[]
     */
    public $ExportItemsResponseMessage = array();

    /**
     * Contains the status and result of a single FindFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FindFolderResponseMessageType[]
     */
    public $FindFolderResponseMessage = array();

    /**
     * Contains the status and result of a single FindItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FindItemResponseMessageType[]
     */
    public $FindItemResponseMessage = array();

    /**
     * Contains the status and result of a single FindMailboxStatisticsByKeyword
     * request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\FindMailboxStatisticsByKeywordsResponseMessageType[]
     */
    public $FindMailboxStatisticsByKeywordsResponseMessage = array();

    /**
     * Contains the status and result of a single GetAttachment request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\AttachmentInfoResponseMessageType[]
     */
    public $GetAttachmentResponseMessage = array();

    /**
     * Contains the status and result of a single GetClientAccessToken request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\GetClientAccessTokenResponseMessageType[]
     */
    public $GetClientAccessTokenResponseMessage = array();

    /**
     * Specifies the response message for a GetConversationItems request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\GetConversationItemsResponseMessageType[]
     */
    public $GetConversationItemsResponseMessage = array();

    /**
     * Contains the status and result of a single GetEvents request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\GetEventsResponseMessageType[]
     */
    public $GetEventsResponseMessage = array();

    /**
     * Contains the status and result of a single GetFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $GetFolderResponseMessage = array();

    /**
     * Contains the status and result of a single GetItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType[]
     */
    public $GetItemResponseMessage = array();

    /**
     * Contains the status and results of a GetReminders request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\GetRemindersResponseMessageType[]
     */
    public $GetRemindersResponse = array();

    /**
     * Contains the status and results of a GetRoomLists request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetRoomListsResponseMessageType[]
     */
    public $GetRoomListsResponse = array();

    /**
     * Contains the status and results of a GetRooms request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetRoomsResponseMessageType[]
     */
    public $GetRoomsResponse = array();

    /**
     * Contains the status and result of a single GetServerTimeZones request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetServerTimeZonesResponseMessageType[]
     */
    public $GetServerTimeZonesResponseMessage = array();

    /**
     * Contains the status and results of a GetSharingFolder request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetSharingFolderResponseMessageType[]
     */
    public $GetSharingFolderResponseMessage = array();

    /**
     * Contains the status and results of a GetSharingMetadata request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetSharingMetadataResponseMessageType[]
     */
    public $GetSharingMetadataResponseMessage = array();

    /**
     * Contains the status and result of a single GetStreamingEvents request.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Response\GetStreamingEventsResponseMessageType[]
     */
    public $GetStreamingEventsResponseMessage = array();

    /**
     * Contains the status and results of a GetUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\GetUserConfigurationResponseMessageType[]
     */
    public $GetUserConfigurationResponseMessage = array();

    /**
     * Contains the status and results of a GetUserPhoto request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\GetUserPhotoResponseMessageType[]
     */
    public $GetUserPhotoResponseMessage = array();

    /**
     * Defines a response message for a MarkAllItemsAsRead request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $MarkAllItemsAsReadResponseMessage = array();

    /**
     * Defines a response message for a MarkAsJunk request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\MarkAsJunkResponseMessageType[]
     */
    public $MarkAsJunkResponseMessage = array();

    /**
     * Contains the status and result of a single MoveFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $MoveFolderResponseMessage = array();

    /**
     * Contains the status and result of a single MoveItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ItemInfoResponseMessageType[]
     */
    public $MoveItemResponseMessage = array();

    /**
     * Contains the status and results of a PerformReminderAction request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\PerformReminderActionResponseMessageType[]
     */
    public $PerformReminderActionResponse = array();

    /**
     * Contains the status and results of a RefreshSharingFolder request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\RefreshSharingFolderResponseMessageType[]
     */
    public $RefreshSharingFolderResponseMessage = array();

    /**
     * Contains the status and result of a ResolveNames request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResolveNamesResponseMessageType[]
     */
    public $ResolveNamesResponseMessage = array();

    /**
     * Contains the status and result of a SearchMailboxes request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\SearchMailboxesResponseMessageType[]
     */
    public $SearchMailboxesResponseMessage = array();

    /**
     * Contains the status and result of a single SendItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $SendItemResponseMessage = array();

    /**
     * Contains the status and result of a single SendNotification request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\SendNotificationResponseMessageType[]
     */
    public $SendNotificationResponseMessage = array();

    /**
     * Contains the status and result of a SetHoldOnMailboxes request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Response\SetHoldOnMailboxesResponseMessageType[]
     */
    public $SetHoldOnMailboxesResponseMessage = array();

    /**
     * Contains the status and result of a single Subscribe request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\SubscribeResponseMessageType[]
     */
    public $SubscribeResponseMessage = array();

    /**
     * Contains the status and result of a SyncFolderHierarchy request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\SyncFolderHierarchyResponseMessageType[]
     */
    public $SyncFolderHierarchyResponseMessage = array();

    /**
     * Contains the status and result of a SyncFolderItems request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\SyncFolderItemsResponseMessageType[]
     */
    public $SyncFolderItemsResponseMessage = array();

    /**
     * Contains the status and result of a single Unsubscribe request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $UnsubscribeResponseMessage = array();

    /**
     * Contains the status and result of a single UpdateFolder request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType[]
     */
    public $UpdateFolderResponseMessage = array();

    /**
     * Contains the status and result of a single UpdateItem request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\UpdateItemResponseMessageType[]
     */
    public $UpdateItemResponseMessage = array();

    /**
     * Contains the status and results of an UpdateUserConfiguration request.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType[]
     */
    public $UpdateUserConfigurationResponseMessage = array();

    /**
     * Contains the status and results of a single UploadItems request.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\Response\UploadItemsResponseMessageType[]
     */
    public $UploadItemsResponseMessage = array();
}
