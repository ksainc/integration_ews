<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
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

namespace OCA\EWS\Service\Remote;

use DateTime;
use Exception;
use Throwable;
use Psr\Log\LoggerInterface;

use OCA\EWS\AppInfo\Application;

use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Components\EWS\Enumeration\ContainmentComparisonType;
use OCA\EWS\Components\EWS\Enumeration\ContainmentModeType;
use OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType;
use OCA\EWS\Components\EWS\Enumeration\DistinguishedFolderIdNameType;
use OCA\EWS\Components\EWS\Enumeration\FolderQueryTraversalType;
use OCA\EWS\Components\EWS\Enumeration\ResponseClassType;
use OCA\EWS\Components\EWS\Enumeration\UnindexedFieldURIType;


class RemoteCommonService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * Service to make requests to Ews v3 (JSON) API
	 */
	public function __construct (string $appName,
								LoggerInterface $logger) {
		$this->logger = $logger;
	}

	/**
     * retrieve list of all folders starting with root folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Folder Object on success / Null on failure
	 */
	public function fetchFolders(EWSClient $DataStore, string $base = 'D', object $additional = null): ?object {
		
		// construct the request
		$request = new \OCA\EWS\Components\EWS\Type\FindFolderType();
		// define start
		$request->ParentFolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		$request->ParentFolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType('root');
		// define recursion
		$request->Traversal = 'Deep';
		// define required base properties
		$request->FolderShape = new \OCA\EWS\Components\EWS\Type\FolderResponseShapeType();
		if ($base == 'A') {
			$request->FolderShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->FolderShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->FolderShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->FolderShape->AdditionalProperties = $additional;
		}
		// execute request
		$response = $DataStore->FindFolder($request);
		// process response
		$response = $response->ResponseMessages->FindFolderResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->RootFolder->Folders;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve list of specific folders starting with root folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $type - Folder Type
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Folder Object on success / Null on failure
	 */
	public function fetchFoldersByType(EWSClient $DataStore, string $type, string $base = 'D', object $additional = null): object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\FindFolderType();
		$request->FolderShape = new \OCA\EWS\Components\EWS\Type\FolderResponseShapeType();
		// define start
		$request->ParentFolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		$request->ParentFolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType('root');
		// define recursion
		$request->Traversal = 'Deep';
		// define required base properties
		if ($base == 'A') {
			$request->FolderShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->FolderShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->FolderShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->FolderShape->AdditionalProperties = $additional;
		}
		// define search criteria
		$request->Restriction = new \OCA\EWS\Components\EWS\Type\RestrictionType();
		$request->Restriction->IsEqualTo = new \OCA\EWS\Components\EWS\Type\IsEqualToType();
		$request->Restriction->IsEqualTo->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:FolderClass');
		$request->Restriction->IsEqualTo->FieldURIOrConstant = new \OCA\EWS\Components\EWS\Type\FieldURIOrConstantType();
		$request->Restriction->IsEqualTo->FieldURIOrConstant->Constant = new \OCA\EWS\Components\EWS\Type\ConstantValueType($type);
		// execute request
		$response = $DataStore->FindFolder($request);
		// process response
		$response = $response->ResponseMessages->FindFolderResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->RootFolder->Folders;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve all information for specific folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Folder Object on success / Null on failure
	 */
	public function fetchFolder(EWSClient $DataStore, string $fid, bool $ftype = false, string $base = 'D', object $additional = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\GetFolderType();
		// define target
		$request->FolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		if ($ftype) {
			$request->FolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->FolderIds->FolderId[] = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define required base properties
		$request->FolderShape = new \OCA\EWS\Components\EWS\Type\FolderResponseShapeType();
		if ($base == 'A') {
			$request->FolderShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->FolderShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->FolderShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->FolderShape->AdditionalProperties = $additional;
		}
		// execute request
		$response = $DataStore->GetFolder($request);
		$response = $response->ResponseMessages->GetFolderResponseMessage;
		// process response
		$data = null;
		foreach ($response as $response_data) {
			// make sure the request succeeded.
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
			} else {
				$data = $response_data->Folders;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * create folder in remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $fid - Item Data
	 * 
	 * @return object Folders Object on success / Null on failure
	 */
	public function createFolder(EWSClient $DataStore, string $fid, object $data, bool $ftype = false): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\CreateFolderType();
		// define target
		$request->ParentFolderId = new \OCA\EWS\Components\EWS\Type\TargetFolderIdType();
		if ($ftype) {
			$request->ParentFolderId->DistinguishedFolderId = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->ParentFolderId->FolderId = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define object to create
		$request->Folders = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfFoldersType();
		if ($data instanceof \OCA\EWS\Components\EWS\Type\CalendarFolderType) {
			$request->Folders->CalendarFolder[] = $data;
		}
		elseif ($data instanceof \OCA\EWS\Components\EWS\Type\ContactsFolderType) {
			$request->Folders->ContactsFolder[] = $data;
		}
		elseif ($data instanceof \OCA\EWS\Components\EWS\Type\FolderType) {
			$request->Folders->Folder[] = $data;
		}
		elseif ($data instanceof \OCA\EWS\Components\EWS\Type\SearchFolderType) {
			$request->Folders->SearchFolder[] = $data;
		}
		elseif ($data instanceof \OCA\EWS\Components\EWS\Type\TasksFolderType) {
			$request->Folders->TasksFolder[] = $data;
		}
		// execute request
		$response = $DataStore->CreateFolder($request);
		// process response
		$response = $response->ResponseMessages->CreateFolderResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// make sure the request succeeded.
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
			} else {
				$data = $response_data->Folders;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * delete folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $ids - Collection Id's List
	 * @param string $type - 
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function deleteFolder(EWSClient $DataStore, array $batch = null, string $type = 'SoftDelete'): ?bool {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\DeleteFolderType();
		$request->DeleteType = $type;
		// define objects to delete
		$request->FolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType($batch);
		// execute request
		$response = $DataStore->DeleteFolder($request);
		// process response
		$response = $response->ResponseMessages->DeleteFolderResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = true;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve list of changes for specific folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $state - Folder Synchronization State
	 * @param bool $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * @param int $max - Maximum Number of changes to list
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Folder Changes Object on success / Null on failure
	 */
	public function fetchFolderChanges(EWSClient $DataStore, string $fid, string $state, bool $ftype = false, int $max = 512, string $base = 'I', object $additional = null): object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\SyncFolderItemsType();
		// define target
		$request->SyncFolderId = new \OCA\EWS\Components\EWS\Type\TargetFolderIdType();
		if ($ftype) {
			$request->SyncFolderId->DistinguishedFolderId = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->SyncFolderId->FolderId = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define start
		$request->SyncState = $state;
		$request->MaxChangesReturned = $max;
		// define required base properties
		$request->ItemShape = new \OCA\EWS\Components\EWS\Type\ItemResponseShapeType();
		if ($base == 'A') {
			$request->ItemShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->ItemShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->ItemShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->ItemShape->AdditionalProperties = $additional;
		}
		else {
			$request->ItemShape->AdditionalProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		}
		// define required essential properties
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:id',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3007',
			'SystemTime'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3008',
			'SystemTime'
		);
		// execute request
		$response = $DataStore->SyncFolderItems($request);
		// process response
		$response = $response->ResponseMessages->SyncFolderItemsResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->Changes;
				$data->SyncToken = $response_data->SyncState;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve all item ids in specific folder from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * @param string $ioff - Items Offset
	 * 
	 * @return object Item Object on success / Null on failure
	 */
	public function fetchItemsIds(EWSClient $DataStore, string $fid, bool $ftype = false, int $ioff = 0): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\FindItemType();
		// define target
		$request->ParentFolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		if ($ftype) {
			$request->ParentFolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->ParentFolderIds->FolderId[] = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define recursion
		$request->Traversal = 'Shallow';
		// define required base properties
		$request->ItemShape = new \OCA\EWS\Components\EWS\Type\ItemResponseShapeType();
		$request->ItemShape->BaseShape = 'IdOnly';
		// define required additional properties
		$request->ItemShape->AdditionalProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		// define required essential properties
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		// define paging
		$request->IndexedPageItemView = new \OCA\EWS\Components\EWS\Type\IndexedPageViewType('Beginning', $ioff, 512);
		// execute request
		$response = $DataStore->FindItem($request);
		// process response
		$response = $response->ResponseMessages->FindItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// make sure the request succeeded.
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->RootFolder->Items;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve information for specific item from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $uuid - Item UUID
	 * @param string $fid - Folder ID
	 * @param string $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Item Object on success / Null on failure
	 */
	public function findItem(EWSClient $DataStore, string $fid, object $restriction, bool $ftype = false, string $base = 'D', object $additional = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\FindItemType();
		// define target
		$request->ParentFolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		if ($ftype) {
			$request->ParentFolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->ParentFolderIds->FolderId[] = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define recursion
		$request->Traversal = 'Shallow';
		// define required base properties
		$request->ItemShape = new \OCA\EWS\Components\EWS\Type\ItemResponseShapeType();
		if ($base == 'A') {
			$request->ItemShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->ItemShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->ItemShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->ItemShape->AdditionalProperties = $additional;
		}
		else {
			$request->ItemShape->AdditionalProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		}
		// define required essential properties
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:id',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3007',
			'SystemTime'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3008',
			'SystemTime'
		);
		// define paging
		$request->IndexedPageItemView = new \OCA\EWS\Components\EWS\Type\IndexedPageViewType('Beginning', 0, 512);
		// define criteria
		$request->Restriction = $restriction;
		// execute request
		$response = $DataStore->FindItem($request);
		// process response
		$response = $response->ResponseMessages->FindItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->RootFolder->Items;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve all information for specific item by uuid from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $uuid - Item UUID
	 * @param string $fid - Folder ID
	 * @param string $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return array Item Object on success / Null on failure
	 */
	public function findItemByUUID(EWSClient $DataStore, string $fid, string $uuid, bool $ftype = false, string $base = 'D', object $additional = null): ?array {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\FindItemType();
		// define target
		$request->ParentFolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		if ($ftype) {
			$request->ParentFolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($fid);
		} else {
			$request->ParentFolderIds->FolderId[] = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		}
		// define recursion
		$request->Traversal = 'Shallow';
		// define required base properties
		$request->ItemShape = new \OCA\EWS\Components\EWS\Type\ItemResponseShapeType();
		if ($base == 'A') {
			$request->ItemShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->ItemShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->ItemShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->ItemShape->AdditionalProperties = $additional;
		}
		else {
			$request->ItemShape->AdditionalProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		}
		// define required essential properties
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:id',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3007',
			'SystemTime'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3008',
			'SystemTime'
		);
		// define paging
		$request->IndexedPageItemView = new \OCA\EWS\Components\EWS\Type\IndexedPageViewType('Beginning', 0, 512);
		// define criteria
		$request->Restriction = new \OCA\EWS\Components\EWS\Type\RestrictionType();
		$request->Restriction->IsEqualTo = new \OCA\EWS\Components\EWS\Type\IsEqualToType();
		$request->Restriction->IsEqualTo->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		$request->Restriction->IsEqualTo->FieldURIOrConstant = new \OCA\EWS\Components\EWS\Type\FieldURIOrConstantType();
		$request->Restriction->IsEqualTo->FieldURIOrConstant->Constant = new \OCA\EWS\Components\EWS\Type\ConstantValueType($uuid);
		// execute request
		$response = $DataStore->FindItem($request);
		// process response
		$response = $response->ResponseMessages->FindItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				foreach ($response_data->Items as $items) {
					if (count($items) > 0) {
						$data = $items;
						break;
					}
				}
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve all information for specific item from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param array $ioc - Collection of Id Objects
	 * @param string $base - Base Properties / D - Default / A - All / I - ID's
	 * @param object $additional - Additional Properties object of NonEmptyArrayOfPathsToElementType
	 * 
	 * @return object Item Object on success / Null on failure
	 */
	public function fetchItem(EWSClient $DataStore, array $ioc, string $base = 'D', object $additional = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\GetItemType();
		// define target
		$request->ItemIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType();
		$request->ItemIds->ItemId = $ioc;
		// define required base properties
		$request->ItemShape = new \OCA\EWS\Components\EWS\Type\ItemResponseShapeType();
		if ($base == 'A') {
			$request->ItemShape->BaseShape = 'AllProperties';
		}
		elseif ($base == 'I') {
			$request->ItemShape->BaseShape = 'IdOnly';
		}
		else  {
			$request->ItemShape->BaseShape = 'Default';
		}
		// define required additional properties
		if ($additional instanceof \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType) {
			$request->ItemShape->AdditionalProperties = $additional;
		}
		else {
			$request->ItemShape->AdditionalProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
		}
		// define required essential properties
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:id',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			'PublicStrings',
			null,
			null,
			'DAV:uid',
			null,
			'String'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3007',
			'SystemTime'
		);
		$request->ItemShape->AdditionalProperties->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
			null,
			null,
			null,
			null,
			'0x3008',
			'SystemTime'
		);
		// execute request
		$response = $DataStore->GetItem($request);
		// process response
		$response = $response->ResponseMessages->GetItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->Items;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * create item in remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $fid - Item Data
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function createItem(EWSClient $DataStore, string $fid, object $data): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\CreateItemType();
		// define target
		$request->SavedItemFolderId = new \OCA\EWS\Components\EWS\Type\TargetFolderIdType();
		$request->SavedItemFolderId->FolderId = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		// define objects to create
		$request->Items = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAllItemsType();
		if (is_a($data, 'OCA\EWS\Components\EWS\Type\ContactItemType')) {
			$request->Items->Contact[] = $data;
		} elseif (is_a($data, 'OCA\EWS\Components\EWS\Type\CalendarItemType')) {
			$request->Items->CalendarItem[] = $data;
		}
		// execute request
		$response = $DataStore->CreateItem($request);
		// process response
		$response = $response->ResponseMessages->CreateItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// make sure the request succeeded.
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				foreach ($response_data->Items as $items) {
					if (count($items) > 0) {
						$data = $items[0];
						break;
					}
				}
			}
		}
		// return object or null
		return $data;

	}

	/**
     * update item in remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $fid - Folder ID
	 * @param string $iid - Item ID
	 * @param string $a - Item Append Commands
	 * @param string $u - Item Update Commands
	 * @param string $d - Item Delete Commands
	 * 
	 * @return object Items Array on success / Null on failure
	 */
	public function updateItem(EWSClient $DataStore, string $fid, string $iid, array $additions = null, array $modifications = null, array $deletions = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\UpdateItemType();
		$request->ConflictResolution = 'AlwaysOverwrite';
		// define target folder
		$request->SavedItemFolderId = new \OCA\EWS\Components\EWS\Type\TargetFolderIdType();
		$request->SavedItemFolderId->FolderId = new \OCA\EWS\Components\EWS\Type\FolderIdType($fid);
		// define target object and changes
		$request->ItemChanges[] = new \OCA\EWS\Components\EWS\Type\ItemChangeType(
			new \OCA\EWS\Components\EWS\Type\ItemIdType($iid),
			new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType($additions, $modifications, $deletions)
		);
		// execute request
		$response = $DataStore->UpdateItem($request);
		// process response
		$response = $response->ResponseMessages->UpdateItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				foreach ($response_data->Items as $items) {
					if (count($items) > 0) {
						$data = $items[0];
						break;
					}
				}
			}
		}
		// return object or null
		return $data;

	}

	/**
     * delete item in remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $ids - Item ID's Array
	 * @param string $fid - Item Data
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function deleteItem(EWSClient $DataStore, array $ids = null, string $type = 'SoftDelete'): ?bool {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\DeleteItemType();
		$request->DeleteType = $type;
		// define objects to delete
		$request->ItemIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseItemIdsType($ids);
		// execute request
		$response = $DataStore->DeleteItem($request);
		// process response
		$response = $response->ResponseMessages->DeleteItemResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = true;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * retrieve item attachment(s) from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $ids - Attachement ID's (array)
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function fetchAttachment(EWSClient $DataStore, array $batch): ?array {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\GetAttachmentType();
		// define target(s)
		$request->AttachmentIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRequestAttachmentIdsType();
		foreach ($batch as $entry) {
			$request->AttachmentIds->AttachmentId[] = new \OCA\EWS\Components\EWS\Type\RequestAttachmentIdType((String) $entry);
		}
		// execute request
		$response = $DataStore->GetAttachment($request);
		// process response
		$response = $response->ResponseMessages->GetAttachmentResponseMessage;
		$data = array();
		foreach ($response as $entry) {
			// make sure the request succeeded.
			if ($entry->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $entry->ResponseCode;
				$message = $entry->MessageText;
				continue;
			} else {
				$data = array_merge($data, (array) $entry->Attachments->FileAttachment, (array) $entry->Attachments->ItemAttachment);
			}
		}
		// return object or null
		return $data;

	}

	/**
     * create item attachment(s) from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param array $batch - Collection of FileAttachmentType Objects
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function createAttachment(EWSClient $DataStore, string $iid, array $batch): array {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\CreateAttachmentType();
		// define target
		$request->ParentItemId = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);
		// define objects to create
		$request->Attachments = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfAttachmentsType();
		foreach ($batch as $entry) {
			if (is_a($entry, 'OCA\EWS\Components\EWS\Type\FileAttachmentType')) {
				$request->Attachments->FileAttachment[] = $entry;
			}
		}
		// execute request
		$response = $DataStore->CreateAttachment($request);
		// process response
		$response = $response->ResponseMessages->CreateAttachmentResponseMessage;
		$data = array();
		foreach ($response as $entry) {
			// make sure the request succeeded.
			if ($entry->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $entry->ResponseCode;
				$message = $entry->MessageText;
				continue;
			} else {
				$data = array_merge($data, (array) $entry->Attachments->FileAttachment, (array) $entry->Attachments->ItemAttachment);
			}
		}
		// return object or null
		return $data;

	}

	/**
     * delete item attachment(s) from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param array $batch - Collection of String Attachemnt Id(s)
	 * 
	 * @return object Attachement Collection Object on success / Null on failure
	 */
	public function deleteAttachment(EWSClient $DataStore, array $batch): array {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\DeleteAttachmentType();
		// define target(s) to delete
		$request->AttachmentIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfRequestAttachmentIdsType();
		foreach ($batch as $entry) {
			$request->AttachmentIds->AttachmentId[] = new \OCA\EWS\Components\EWS\Type\RequestAttachmentIdType((String) $entry);
		}
		// execute request
		$response = $DataStore->DeleteAttachment($request);
		// process response
		$response = $response->ResponseMessages->DeleteAttachmentResponseMessage;
		// construct result collection
		$data = array();
		foreach ($response as $key => $entry) {
			// make sure the request succeeded.
			if ($entry->ResponseClass != ResponseClassType::SUCCESS) {
				$data[] = array('Id' => $batch[$key], 'Status' => false, 'reason' => $entry->MessageText);
			} else {
				$data[] = array('Id' => $batch[$key], 'Status' => true);
			}
		}
		// return result collection
		return $data;

	}

	/**
     * retrieve time zone information from remote storage
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * @param string $uuid - Item UUID
	 * @param string $fid - Folder ID
	 * @param string $ftype - Folder ID Type (True - Distinguished / False - Normal)
	 * 
	 * @return object Item Object on success / Null on failure
	 */
	public function fetchTimeZone(EWSClient $DataStore, string $zone = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\GetServerTimeZonesType();
		// define target
		if (!empty($zone)) {
			$request->Ids = \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfTimeZoneIdType();
			$request->Ids->Id[] = $zone;
		}
		// execute request
		$response = $DataStore->GetServerTimeZones($request);
		// process response
		$response = $response->ResponseMessages->GetServerTimeZonesResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->TimeZoneDefinitions;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * connect to event nofifications
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * 
	 * @return object Items Object on success / Null on failure
	 */
	public function connectEvents(EWSClient $DataStore, int $duration, array $ids = null, array $dids = null, array $types = null): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\SubscribeType();
		$request->PullSubscriptionRequest = new \OCA\EWS\Components\EWS\Type\PullSubscriptionRequestType();
		$request->PullSubscriptionRequest->FolderIds = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType();
		$request->PullSubscriptionRequest->EventTypes = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfNotificationEventTypesType();
		$request->PullSubscriptionRequest->Timeout = $duration;
		// define target(s)
		if (isset($ids)) {
			foreach ($ids as $entry) {
				$request->PullSubscriptionRequest->FolderIds->FolderId[] = new \OCA\EWS\Components\EWS\Type\FolderIdType($entry);
			}
		}
		if (isset($dids)) {
			foreach ($dids as $entry) {
				$request->PullSubscriptionRequest->FolderIds->DistinguishedFolderId[] = new \OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType($entry);
			}
		}
		// define types(s)
		if (isset($types)) {
			$request->PullSubscriptionRequest->EventTypes->EventType = $types;
		}
		else {
			$request->PullSubscriptionRequest->EventTypes->EventType = ['CreatedEvent', 'ModifiedEvent', 'DeletedEvent', 'NewMailEvent', 'CopiedEvent', 'MovedEvent'];
		}
		// execute request
		$response = $DataStore->Subscribe($request);
		// process response
		$response = $response->ResponseMessages->SubscribeResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = (object) ['Id' => $response_data->SubscriptionId, 'Token' => $response_data->Watermark];
			}
		}
		// return object or null
		return $data;

	}

	/**
     * disconnect from event nofifications
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * 
	 * @return object Items Object on success / Null on failure
	 */
	public function disconnectEvents(EWSClient $DataStore, string $id): ?bool {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\UnsubscribeType();
		$request->SubscriptionId = $id;
		// execute request
		$response = $DataStore->Unsubscribe($request);
		// process response
		$response = $response->ResponseMessages->UnsubscribeResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = true;
			}
		}
		// return object or null
		return $data;

	}

	/**
     * observe event nofifications
     * 
	 * @param EWSClient $DataStore - Storage Interface
	 * 
	 * @return object Items Object on success / Null on failure
	 */
	public function fetchEvents(EWSClient $DataStore, string $id, string $token): ?object {
		
		// construct request
		$request = new \OCA\EWS\Components\EWS\Request\GetEventsType();
		$request->SubscriptionId = $id;
		$request->Watermark = $token;
		// execute request
		$response = $DataStore->GetEvents($request);
		// process response
		$response = $response->ResponseMessages->GetEventsResponseMessage;
		$data = null;
		foreach ($response as $response_data) {
			// check response for failure
			if ($response_data->ResponseClass != ResponseClassType::SUCCESS) {
				$code = $response_data->ResponseCode;
				$message = $response_data->MessageText;
				continue;
			} else {
				$data = $response_data->Notification;
			}
		}
		// return object or null
		return $data;

	}

}
