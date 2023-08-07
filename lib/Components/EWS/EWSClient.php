<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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

namespace OCA\EWS\Components\EWS;

use OCA\EWS\Components\EWS\EWSSoap;

/**
 * Base class of the Exchange Web Services application.
 *
 * @package OCA\EWS\Components\EWS\EWSClient
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class EWSClient
{
    /**
     * Microsoft Exchange 2007
     *
     * @var string
     */
    const VERSION_2007 = 'Exchange2007';

    /**
     * Microsoft Exchange 2007 SP1
     *
     * @var string
     */
    const VERSION_2007_SP1 = 'Exchange2007_SP1';

    /**
     * Microsoft Exchange 2007 SP2
     *
     * @var string
     */
    const VERSION_2009 = 'Exchange2009';

    /**
     * Microsoft Exchange 2010
     *
     * @var string
     */
    const VERSION_2010 = 'Exchange2010';

    /**
     * Microsoft Exchange 2010 SP1
     *
     * @var string
     */
    const VERSION_2010_SP1 = 'Exchange2010_SP1';

    /**
     * Microsoft Exchange 2010 SP2
     *
     * @var string
     */
    const VERSION_2010_SP2 = 'Exchange2010_SP2';

    /**
     * Microsoft Exchange 2013.
     *
     * @var string
     */
    const VERSION_2013 = 'Exchange2013';

    /**
     * Microsoft Exchange 2013 SP1.
     *
     * @var string
     */
    const VERSION_2013_SP1 = 'Exchange2013_SP1';

    /**
     * Microsoft Exchange 2016.
     *
     * @var string
     */
    const VERSION_2016 = 'Exchange2016';

    /**
     * cURL options to be passed to the SOAP client.
     *
     * @var array
     */
    protected $curl_options = array();

    /**
     * SOAP headers used for requests.
     *
     * @var \SoapHeader[]
     */
    protected $headers = array();

    /**
     * Password to use when connecting to the Exchange server.
     *
     * @var string
     */
    protected $password;

    /**
     * Location of the Exchange server.
     *
     * @var string
     */
    protected $server;

    /**
     * SOAP client used to make the request.
     *
     * @var null \OCA\EWS\Components\EWS\EWSSoap
     */
    protected $soap;

    /**
     * Timezone to be used for all requests.
     *
     * @var string
     */
    protected $timezone;

    /**
     * Username to use when connecting to the Exchange server.
     *
     * @var string
     */
    protected $username;

    /**
     * Exchange impersonation
     *
     * @var \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType
     */
    protected $impersonation;

    /**
     * Microsoft Exchange version that we are going to connect to
     *
     * @var string
     *
     * @see EWSClient::VERSION_2007
     * @see EWSClient::VERSION_2007_SP1
     * @see EWSClient::VERSION_2007_SP2
     * @see EWSClient::VERSION_2007_SP3
     * @see EWSClient::VERSION_2010
     * @see EWSClient::VERSION_2010_SP1
     * @see EWSClient::VERSION_2013
     * @see EWSClient::VERSION_2013_SP1
     * @see EWSClient::VERSION_2016
     * 
     */
    protected $version;

    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $version
     *   One of the EWSClient::VERSION_* constants.
     */
    public function __construct(
        $server = null,
        $username = null,
        $password = null,
        $version = self::VERSION_2013
    ) {
        // Set the object properties.
        $this->setServer($server);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setVersion($version);
    }

    /**
     * Returns the SOAP Client that may be used to make calls against the server
     *
     * @return \OCA\EWS\Components\EWS\EWSSoap
     */
    public function getClient()
    {
        // If the SOAP client has yet to be initialized then do so now.
        if (empty($this->soap)) {
            $this->initializeSoap();
        }

        return $this->soap;
    }

    /**
     * Sets the cURL options that will be set on the SOAP client.
     *
     * @param array $options
     */
    public function setCurlOptions(array $options)
    {
        $this->curl_options = $options;

        // We need to reinitialize the SOAP client.
        $this->soap = null;
    }

    /**
     * Sets the impersonation property
     *
     * @param \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType $impersonation
     */
    public function setImpersonation($impersonation)
    {
        $this->impersonation = $impersonation;

        // We need to re-build the SOAP headers.
        $this->headers = array();
    }

    /**
     * Sets the password property
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        // We need to reinitialize the SOAP client.
        $this->soap = null;
    }

    /**
     * Sets the server property
     *
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;

        // We need to reinitialize the SOAP client.
        $this->soap = null;
    }

    /**
     * Sets the timezone to be used for all requests.
     *
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        // We need to re-build the SOAP headers.
        $this->headers = array();
    }

    /**
     * Sets the user name property
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        // We need to reinitialize the SOAP client.
        $this->soap = null;
    }

    /**
     * Sets the version property
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;

        // We need to re-build the SOAP headers.
        $this->headers = array();
    }

    /**
     * Adds one or more delegates to a principal's mailbox and sets specific
     * access permissions.
     *
     * @since Exchange 2007 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\AddDelegateType $request
     * @return \OCA\EWS\Components\EWS\Response\AddDelegateResponseMessageType
     */
    public function AddDelegate($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds a distribution group to the instant messaging (IM) list in the
     * Unified Contact Store.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\AddDistributionGroupToImListType $request
     * @return \OCA\EWS\Components\EWS\Response\AddDistributionGroupToImListResponseMessageType
     */
    public function AddDistributionGroupToImList($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds an existing instant messaging (IM) contact to a group.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\AddImContactToGroup $request
     * @return \OCA\EWS\Components\EWS\Response\AddImContactToGroupResponseMessageType
     */
    public function AddImContactToGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds a new instant messaging (IM) group to a mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\AddImGroupType $request
     * @return \OCA\EWS\Components\EWS\Response\AddImGroupResponseMessageType
     */
    public function AddImGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds a new contact to an instant messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\AddNewImContactToGroup $request
     * @return \OCA\EWS\Components\EWS\Response\AddNewImContactToGroupResponseMessageType
     */
    public function AddNewImContactToGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds a new contact to a group based on a contact's phone number.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\AddNewTelUriContactToGroupType $request
     * @return \OCA\EWS\Components\EWS\Response\AddNewTelUriContactToGroupResponse
     */
    public function AddNewTelUriContactToGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Sets a one-time or follow up action on all the items in a conversation.
     *
     * This operation allows you to categorize, move, copy, delete, and set the
     * read state on all items in a conversation. Actions can also be set for
     * new messages in a conversation.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\ApplyConversationActionType $request
     * @return \OCA\EWS\Components\EWS\Response\ApplyConversationActionResponseType
     */
    public function ApplyConversationAction($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Moves an item into the mailbox user's archive mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\ArchiveItemType $request
     * @return \OCA\EWS\Components\EWS\Response\ArchiveItemResponse
     */
    public function ArchiveItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Converts item and folder identifiers between formats that are accepted by
     * Exchange Online, Exchange Online as part of Office 365, and on-premises
     * versions of Exchange.
     *
     * @since Exchange 2007 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\ConvertIdType $request
     * @return \OCA\EWS\Components\EWS\Response\ConvertIdResponseType
     */
    public function ConvertId($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Copies folders in a mailbox.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CopyFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\CopyFolderResponseType
     */
    public function CopyFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Copies items and puts the items in a different folder.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CopyItemType $request
     * @return \OCA\EWS\Components\EWS\Response\CopyItemResponseType
     */
    public function CopyItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates either an item or file attachment and attaches it to the
     * specified item.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateAttachmentType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateAttachmentResponseType
     */
    public function CreateAttachment($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates folders, calendar folders, contacts folders, tasks folders, and
     * search folders.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateFolderResponseType
     */
    public function CreateFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates a folder hierarchy.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateFolderPathType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateFolderPathResponseType
     */
    public function CreateFolderPath($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates items in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateItemType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateItemResponseType
     */
    public function CreateItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates a managed folder in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateManagedFolderRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateManagedFolderResponseType
     */
    public function CreateManagedFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Creates a user configuration object on a folder.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\CreateUserConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\CreateUserConfigurationResponseType
     */
    public function CreateUserConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Deletes file and item attachments from an existing item in the Exchange
     * store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\DeleteAttachmentType $request
     * @return \OCA\EWS\Components\EWS\Response\DeleteAttachmentResponseType
     */
    public function DeleteAttachment($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Deletes folders from a mailbox.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\DeleteFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\DeleteFolderResponseType
     */
    public function DeleteFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Deletes items in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\DeleteItemType $request
     * @return \OCA\EWS\Components\EWS\Response\DeleteItemResponseType
     */
    public function DeleteItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Deletes a user configuration object on a folder.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\DeleteUserConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\DeleteUserConfigurationResponseType
     */
    public function DeleteUserConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Disables a mail app for Outlook.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\DisableAppType $request
     * @return \OCA\EWS\Components\EWS\Response\DisableAppResponseType
     */
    public function DisableApp($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Terminates a telephone call.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\DisconnectPhoneCallType $request
     * @return \OCA\EWS\Components\EWS\Response\DisconnectPhoneCallResponseMessageType
     */
    public function DisconnectPhoneCall($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Empties folders in a mailbox.
     *
     * Optionally, this operation enables you to delete the subfolders of the
     * specified folder. When a subfolder is deleted, the subfolder and the
     * messages within the subfolder are deleted.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\EmptyFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\EmptyFolderResponseType
     */
    public function EmptyFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Exposes the full membership of distribution lists.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\ExpandDLType $request
     * @return \OCA\EWS\Components\EWS\Response\ExpandDLResponseType
     */
    public function ExpandDL($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Exports items out of a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\ExportItemsType $request
     * @return \OCA\EWS\Components\EWS\Response\ExportItemsResponseType
     */
    public function ExportItems($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Enumerates a list of conversations in a folder.
     *
     * @param \OCA\EWS\Components\EWS\Request\FindConversationType $request
     * @return \OCA\EWS\Components\EWS\Response\FindConversationResponseMessageType
     */
    public function FindConversation($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Finds subfolders of an identified folder and returns a set of properties
     * that describe the set of subfolders.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\FindFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\FindFolderResponseType
     */
    public function FindFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Searches for items that are located in a user’s mailbox.
     *
     * This operation provides many ways to filter and format how search results
     * are returned to the caller.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\FindItemType $request
     * @return \OCA\EWS\Components\EWS\Response\FindItemResponseType
     */
    public function FindItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Finds messages that meet the specified criteria.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\FindMessageTrackingReportRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\FindMessageTrackingReportResponseMessageType
     */
    public function FindMessageTrackingReport($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Returns all persona objects from a specified Contacts folder or retrieves
     * contacts that match a specified query string.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\FindPeopleType $request
     * @return \OCA\EWS\Components\EWS\Response\FindPeopleResponseMessageType
     */
    public function FindPeople($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves app manifests.
     *
     * @since Exchange 2013 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\GetAppManifestsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetAppManifestsResponseType
     */
    public function GetAppManifests($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the URL for the app marketplace that a client can visit to
     * acquire apps to install in a mailbox.
     *
     * @since Exchange 2013 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\GetAppMarketplaceUrl $request
     * @return \OCA\EWS\Components\EWS\Response\GetAppMarketplaceUrlResponseMessageType
     */
    public function GetAppMarketplaceUrl($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves existing attachments on items in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetAttachmentType $request
     * @return \OCA\EWS\Components\EWS\Response\GetAttachmentResponseType
     */
    public function GetAttachment($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Gets a client access token for a mail app for Outlook.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetClientAccessTokenType $request
     * @return \OCA\EWS\Components\EWS\Response\GetClientAccessTokenResponseType
     */
    public function GetClientAccessToken($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves one or more sets of items that are organized in to nodes in a
     * conversation.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetConversationItemsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetConversationItemsResponseType
     */
    public function GetConversationItems($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the delegate settings for a specified mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\GetDelegateType $request
     * @return \OCA\EWS\Components\EWS\Response\GetDelegateResponseMessageType
     */
    public function GetDelegate($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Returns configuration information for in-place holds, saved discovery
     * searches, and the mailboxes that are enabled for discovery search.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetDiscoverySearchConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\GetDiscoverySearchConfigurationResponseMessageType
     */
    public function GetDiscoverySearchConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Used by pull subscription clients to request notifications from the
     * Client Access server.
     *
     * The response returns an array of items and events that have occurred in a
     * mailbox since the last the notification.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetEventsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetEventsResponseType
     */
    public function GetEvents($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Gets folders from the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\GetFolderResponseType
     */
    public function GetFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the mailboxes that are under a specific hold and the associated
     * hold query.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetHoldOnMailboxesType $request
     * @return \OCA\EWS\Components\EWS\Response\GetHoldOnMailboxesResponseMessageType
     */
    public function GetHoldOnMailboxes($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the list of instant messaging (IM) groups and IM contact
     * personas in a mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetImItemListType $request
     * @return \OCA\EWS\Components\EWS\Response\GetImItemListResponseMessageType
     */
    public function GetImItemList($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves information about instant messaging (IM) groups and IM contact
     * personas.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetImItemsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetImItemsResponse
     */
    public function GetImItems($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves Inbox rules in the identified user's mailbox.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetInboxRulesRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\GetInboxRulesResponseType
     */
    public function GetInboxRules($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Gets folders from the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetItemType $request
     * @return \OCA\EWS\Components\EWS\Response\GetItemResponseType
     */
    public function GetItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the mail tips information for the specified mailbox.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetMailTipsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetMailTipsResponseMessageType
     */
    public function GetMailTips($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves tracking information about the specified messages.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetMessageTrackingReportRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\GetMessageTrackingReportResponseMessageType
     */
    public function GetMessageTrackingReport($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves details about items that cannot be indexed.
     *
     * This includes, but is not limited to, the item identifier, an error code,
     * an error description, when an attempt was made to index the item, and
     * additional information about the file.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetNonIndexableItemDetailsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetNonIndexableItemDetailsResponseMessageType
     */
    public function GetNonIndexableItemDetails($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the count of items that cannot be indexed in a mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetNonIndexableItemStatisticsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetNonIndexableItemStatisticsResponseMessageType
     */
    public function GetNonIndexableItemStatistics($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Provides the email account password expiration date for the current user.
     *
     * @since Exchange 2010 SP2
     *
     * @param \OCA\EWS\Components\EWS\Request\GetPasswordExpirationDateType $request
     * @return \OCA\EWS\Components\EWS\Response\GetPasswordExpirationDateResponseMessageType
     */
    public function GetPasswordExpirationDate($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves a set of properties that are associated with a persona.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetPersonaType $request
     * @return \OCA\EWS\Components\EWS\Response\GetPersonaResponseMessageType
     */
    public function GetPersona($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves information about the specified telephone call.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetPhoneCallInformationType $request
     * @return \OCA\EWS\Components\EWS\Response\GetPhoneCallInformationResponseMessageType
     */
    public function GetPhoneCallInformation($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves reminders for calendar and task items.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetRemindersType $request
     * @return \OCA\EWS\Components\EWS\Response\GetRemindersResponseMessageType
     */
    public function GetReminders($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the room lists that are available within the Exchange
     * organization.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetRoomListsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetRoomListsResponseMessageType
     */
    public function GetRoomLists($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the rooms within the specified room list.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\GetRoomsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetRoomsResponseMessageType
     */
    public function GetRooms($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves a scoped set of searchable mailboxes for discovery searches.
     *
     * The scope of searchable mailboxes returned in the response is determined
     * by the search filter and whether distribution group membership is
     * expanded.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetSearchableMailboxesType $request
     * @return \OCA\EWS\Components\EWS\Response\GetSearchableMailboxesResponseMessageType
     */
    public function GetSearchableMailboxes($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieve the timezones supported by the server.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetServerTimeZonesType $request
     * @return \OCA\EWS\Components\EWS\Response\GetServerTimeZonesResponseType
     */
    public function GetServerTimeZones($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves configuration information for the specified type of service.
     *
     * This operation can return configuration settings for the Unified
     * Messaging, Protection Rules, and Mail Tips services.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetServiceConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\GetServiceConfigurationResponseMessageType
     */
    public function GetServiceConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves the local folder identifier of a specified shared folder.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetSharingFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\GetSharingFolderResponseMessageType
     */
    public function GetSharingFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Gets an opaque authentication token that identifies a sharing invitation.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetSharingMetadataType $request
     * @return \OCA\EWS\Components\EWS\Response\GetSharingMetadataResponseMessageType
     */
    public function GetSharingMetadata($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Requests notifications from the Client Access server.
     *
     * The GetStreamingEvents response returns an array of items and events that
     * have occurred in a mailbox since the last the notification.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\GetStreamingEventsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetStreamingEventsResponseType
     */
    public function GetStreamingEvents($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Provides detailed information about the availability of a set of users,
     * rooms, and resources within a specified time period.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetUserAvailabilityRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\GetUserAvailabilityResponseType
     */
    public function GetUserAvailability($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves a user configuration object from a folder.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\GetUserConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\GetUserConfigurationResponseType
     */
    public function GetUserConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Gets a mailbox user's Out of Office (OOF) settings and messages.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\GetUserOofSettingsRequest $request
     * @return \OCA\EWS\Components\EWS\Response\GetUserOofSettingsResponse
     */
    public function GetUserOofSettings($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves a user photo from Active Directory Domain Services (AD DS).
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetUserPhotoType $request
     * @return \OCA\EWS\Components\EWS\Response\GetUserPhotoResponseMessageType
     */
    public function GetUserPhoto($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Retrieves a list of all default, system folder, and personal tags that
     * are associated with a user by means of a system policy or that were
     * applied by the user.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\GetUserRetentionPolicyTagsType $request
     * @return \OCA\EWS\Components\EWS\Response\GetUserRetentionPolicyTagsResponseMessageType
     */
    public function GetUserRetentionPolicyTags($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Installs a mail app for Outlook in a mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\InstallAppType $request
     * @return \OCA\EWS\Components\EWS\Response\InstallAppResponseType
     */
    public function InstallApp($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Sets the IsRead property on all items, in one or more folders, to
     * indicate that all items are either read or unread.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\MarkAllItemsAsRead $request
     * @return \OCA\EWS\Components\EWS\Response\MarkAllItemsAsReadResponseType
     */
    public function MarkAllItemsAsRead($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Adds and removes users from the blocked email list and moves email
     * messages to the Junk Email folder.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\MarkAsJunkType $request
     * @return \OCA\EWS\Components\EWS\Response\MarkAsJunkResponseType
     */
    public function MarkAsJunk($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Moves folders from a specified folder and puts them in another folder.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\MoveFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\MoveFolderResponseType
     */
    public function MoveFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Moves one or more items to a single destination folder.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\MoveItemType $request
     * @return \OCA\EWS\Components\EWS\Response\MoveItemResponseType
     */
    public function MoveItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Initiates a dismiss or snooze action on a reminder.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\PerformReminderActionType $request
     * @return \OCA\EWS\Components\EWS\Response\PerformReminderActionResponseMessageType
     */
    public function PerformReminderAction($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Initiates an outbound call and plays a message over the telephone.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\PlayOnPhoneType $request
     * @return \OCA\EWS\Components\EWS\Response\PlayOnPhoneResponseMessageType
     */
    public function PlayOnPhone($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Refreshes the specified local folder with the latest data from the folder
     * that is being shared.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\RefreshSharingFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\RefreshSharingFolderResponseMessageType
     */
    public function RefreshSharingFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Removes contacts from the Lync instant messaging (IM) list when Lync uses
     * Exchange for the contact store.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\RemoveContactFromImListType $request
     * @return \OCA\EWS\Components\EWS\Response\RemoveContactFromImListResponseMessageType
     */
    public function RemoveContactFromImList($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Removes one or more delegates from a user's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\RemoveDelegateType $request
     * @return \OCA\EWS\Components\EWS\Response\RemoveDelegateResponseMessageType
     */
    public function RemoveDelegate($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Removes a distribution group from the Lync instant messaging (IM) list
     * when Lync uses Exchange for the contact store.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\RemoveDistributionGroupFromImListType $request
     * @return \OCA\EWS\Components\EWS\Response\RemoveDistributionGroupFromImListResponseMessageType
     */
    public function RemoveDistributionGroupFromImList($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Removes a single IM contact from an IM group.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\RemoveImContactFromGroupType $request
     * @return \OCA\EWS\Components\EWS\Response\RemoveImContactFromGroupResponseMessageType
     */
    public function RemoveImContactFromGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Removes a single instant messaging (IM) group from a mailbox.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\RemoveImGroupType $request
     * @return \OCA\EWS\Components\EWS\Response\RemoveImGroupResponseMessageType
     */
    public function RemoveImGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Resolves ambiguous email addresses and display names.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\ResolveNamesType $request
     * @return \OCA\EWS\Components\EWS\Response\ResolveNamesResponseType
     */
    public function ResolveNames($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Searches mailboxes for occurrences of terms in mailbox items.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\SearchMailboxesType $request
     * @return \OCA\EWS\Components\EWS\Response\SearchMailboxesResponseType
     */
    public function SearchMailboxes($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Sends e-mail messages that are located in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\SendItemType $request
     * @return \OCA\EWS\Components\EWS\Response\SendItemResponseType
     */
    public function SendItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Sets a mailbox hold policy on mailboxes.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\SetHoldOnMailboxesType $request
     * @return \OCA\EWS\Components\EWS\Response\SetHoldOnMailboxesResponseMessageType
     */
    public function SetHoldOnMailboxes($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Changes the display name of an instant messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\SetImGroupType $request
     * @return \OCA\EWS\Components\EWS\Response\SetImGroupResponseMessageType
     */
    public function SetImGroup($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Sets a mailbox user's Out of Office (OOF) settings and message.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\SetUserOofSettingsRequest $request
     * @return \OCA\EWS\Components\EWS\Response\SetUserOofSettingsResponse
     */
    public function SetUserOofSettings($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Subscribes client applications to either push or pull notifications.
     *
     * It is important to be aware that the structure of the request messages
     * and responses is different depending on the type of event notification.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\SubscribeType $request
     * @return \OCA\EWS\Components\EWS\Response\SubscribeResponseType
     */
    public function Subscribe($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Synchronizes folders between the computer that is running Microsoft
     * Exchange Server and the client.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\SyncFolderHierarchyType $request
     * @return \OCA\EWS\Components\EWS\Response\SyncFolderHierarchyResponseType
     */
    public function SyncFolderHierarchy($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Synchronizes items between the Exchange server and the client.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\SyncFolderItemsType $request
     * @return \OCA\EWS\Components\EWS\Response\SyncFolderItemsResponseType
     */
    public function SyncFolderItems($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Uninstalls a mail app for Outlook.
     *
     * @since Exchange 2013
     *
     * @param \OCA\EWS\Components\EWS\Request\UninstallAppType $request
     * @return \OCA\EWS\Components\EWS\Response\UninstallAppResponseType
     */
    public function UninstallApp($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Ends a pull notification subscription.
     *
     * Use this operation rather than letting a subscription timeout. This
     * operation is only valid for pull notifications.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\UnsubscribeType $request
     * @return \OCA\EWS\Components\EWS\Response\UnsubscribeResponseType
     */
    public function Unsubscribe($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Updates delegate permissions on a principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\UpdateDelegateType $request
     * @return \OCA\EWS\Components\EWS\Response\UpdateDelegateResponseMessageType
     */
    public function UpdateDelegate($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Modifies properties of an existing item in the Exchange store.
     *
     * Each UpdateFolder operation consists of the following:
     * - A FolderId element that specifies a folder to update.
     * - An internal path of an element in the folder, as specified by the
     *   folder shape, which specifies the data to update.
     * - A folder that contains the new value of the updated field, if the
     *   update is not a deletion.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\UpdateFolderType $request
     * @return \OCA\EWS\Components\EWS\Response\UpdateFolderResponseType
     */
    public function UpdateFolder($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Updates the authenticated user's Inbox rules by applying the specified
     * operations.
     *
     * This operation is used to create an Inbox rule, to set an Inbox rule, or
     * to delete an Inbox rule.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\UpdateInboxRulesRequestType $request
     * @return \OCA\EWS\Components\EWS\Response\UpdateInboxRulesResponseType
     */
    public function UpdateInboxRules($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Used to modify the properties of an existing item in the Exchange store.
     *
     * @since Exchange 2007
     *
     * @param \OCA\EWS\Components\EWS\Request\UpdateItemType $request
     * @return \OCA\EWS\Components\EWS\Response\UpdateItemResponseType
     */
    public function UpdateItem($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Updates a user configuration object on a folder.
     *
     * @since Exchange 2010
     *
     * @param \OCA\EWS\Components\EWS\Request\UpdateUserConfigurationType $request
     * @return \OCA\EWS\Components\EWS\Response\UpdateUserConfigurationResponseType
     */
    public function UpdateUserConfiguration($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Uploads a stream of items into an Exchange mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @param \OCA\EWS\Components\EWS\Request\UploadItemsType $request
     * @return \OCA\EWS\Components\EWS\Response\UploadItemsResponseType
     */
    public function UploadItems($request)
    {
        return $this->makeRequest(__FUNCTION__, $request);
    }

    /**
     * Initializes the EWSSoap object to make a request
     *
     * @return \OCA\EWS\Components\EWS\EWSSoap
     */
    protected function initializeSoap()
    {
        $this->soap = new EWSSoap(
            dirname(__FILE__) . '/Assets/services.wsdl',
            array(
                'user' => $this->username,
                'password' => $this->password,
                'location' => 'https://' . $this->server . '/EWS/Exchange.asmx',
                'classmap' => $this->classMap(),
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            )
        );

        return $this->soap;
    }

    /**
     * The class map used to process SOAP requests and responses.
     *
     * @return string[]
     *
     * @see \OCA\EWS\Components\EWS\ClassMap::getMap()
     */
    protected function classMap()
    {
        $class_map = new ClassMap();

        return $class_map->getMap();
    }

    /**
     * Makes the SOAP call for a request.
     *
     * @param string $operation
     *   The operation to be called.
     * @param \OCA\EWS\Components\EWS\Request $request
     *   The request object for the operation.
     * @return \OCA\EWS\Components\EWS\Response
     *   The response object for the operation.
     *
     * @suppress PhanTypeMismatchReturn
     */
    protected function makeRequest($operation, $request)
    {
        $this->getClient()->__setSoapHeaders($this->soapHeaders());
        $response = $this->soap->{$operation}($request);

        return $this->processResponse($response);
    }

    /**
     * Process a response to verify that it succeeded and take the appropriate
     * action
     *
     * @throws \Exception
     *
     * @param \stdClass $response
     * @return \stdClass
     */
    protected function processResponse($response)
    {
        // If the soap call failed then we need to throw an exception.
        $code = $this->soap->getResponseCode();
        if ($code != 200) {
            throw new \Exception(
                "SOAP client returned status of $code.",
                $code
            );
        }

        return $response;
    }

    /**
     * Builds the soap headers to be included with the request.
     *
     * @return \SoapHeader[]
     */
    protected function soapHeaders()
    {
        // If the headers have already been built, no need to do so again.
        if (!empty($this->headers)) {
            return $this->headers;
        }

        $this->headers = array();

        // Set the schema version.
        $this->headers[] = new \SoapHeader(
            'http://schemas.microsoft.com/exchange/services/2006/types',
            'RequestServerVersion Version="' . $this->version . '"'
        );

        // If impersonation was set then add it to the headers.
        if (!empty($this->impersonation)) {
            $this->headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'ExchangeImpersonation',
                $this->impersonation
            );
        }

        if (!empty($this->timezone)) {
            $this->headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'TimeZoneContext',
                array(
                    'TimeZoneDefinition' => array(
                        'Id' => $this->timezone,
                    )
                )
            );
        }

        return $this->headers;
    }
}
