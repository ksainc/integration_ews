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

use Datetime;
use DateTimeZone;
use DateTimeInterface;
use finfo;
use Psr\Log\LoggerInterface;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Service\Remote\RemoteCommonService;
use OCA\EWS\Components\EWS\EWSClient;
use OCA\EWS\Components\EWS\Type\ContactItemType;
use OCA\EWS\Objects\ContactCollectionObject;
use OCA\EWS\Objects\ContactObject;
use OCA\EWS\Objects\ContactAttachmentObject;

class RemoteContactsService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var RemoteCommonService
	 */
	private $RemoteCommonService;
	/**
	 * @var EWSClient
	 */
	public ?EWSClient $DataStore = null;
    /**
	 * @var Object
	 */
	private ?object $DefaultCollectionProperties = null;
	/**
	 * @var Object
	 */
	private ?object $DefaultItemProperties = null;

	public function __construct (string $appName,
								LoggerInterface $logger,
								RemoteCommonService $RemoteCommonService) {
		$this->logger = $logger;
		$this->RemoteCommonService = $RemoteCommonService;
	}

	/**
	 * retrieve list of collections in remote storage
     * 
     * @since Release 1.0.0
	 *
	 * @return array of collections and properties
	 */
	public function listCollections(): array {

		// execute command
		$cr = $this->RemoteCommonService->fetchFoldersByType($this->DataStore, 'IPF.Contact', 'I', $this->constructDefaultCollectionProperties());
        // process response
		$cl = array();
		if (isset($cr)) {
			foreach ($cr->ContactsFolder as $folder) {
				$cl[] = array('id'=>$folder->FolderId->Id, 'name'=>$folder->DisplayName,'count'=>$folder->TotalCount);
			}
		}
        // return collections
		return $cl;

	}

	/**
     * retrieve properties for specific collection
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
	 * 
	 * @return ContactCollectionObject
	 */
	public function fetchCollection(string $cid): ?ContactCollectionObject {

        // execute command
		$cr = $this->RemoteCommonService->fetchFolder($this->DataStore, $cid, false, 'I', $this->constructDefaultCollectionProperties());
        // process response
		if (isset($cr) && (count($cr->ContactsFolder) > 0)) {
		    $ec = new ContactCollectionObject(
				$cr->ContactsFolder[0]->FolderId->Id,
				$cr->ContactsFolder[0]->DisplayName,
				$cr->ContactsFolder[0]->FolderId->ChangeKey,
				$cr->ContactsFolder[0]->TotalCount
			);
			if (isset($cr->ContactsFolder[0]->ParentFolderId->Id)) {
				$ec->AffiliationId = $cr->ContactsFolder[0]->ParentFolderId->Id;
			}
			return $ec;
		} else {
			return null;
		}
        
    }

	/**
     * create collection in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection Item ID
	 * 
	 * @return ContactCollectionObject
	 */
	public function createCollection(string $cid, string $name, bool $ctype = false): ?ContactCollectionObject {
        
		// construct command object
		$cc = new \OCA\EWS\Components\EWS\Type\ContactsFolderType();
		$cc->DisplayName = $name;
		$cc->FolderClass = 'IPF.Contact';
		// execute command
		$cr = $this->RemoteCommonService->createFolder($this->DataStore, $cid, $cc, $ctype);
        // process response
		if (isset($cr) && (count($cr->ContactsFolder) > 0)) {
            return new ContactCollectionObject(
				$cr->ContactsFolder[0]->FolderId->Id,
				$name,
				$cr->ContactsFolder[0]->FolderId->ChangeKey
			);
		} else {
			return null;
		}

    }

    /**
     * delete collection in remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $cid - Collection ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollection(string $cid): bool {
        
		// construct command object
        $cc = new \OCA\EWS\Components\EWS\Type\FolderIdType($cid);
		// execute command
        $cr = $this->RemoteCommonService->deleteFolder($this->DataStore, array($cc));
		// process response
        if ($cr) {
            return true;
        } else {
            return false;
        }

    }

    /**
	 * retrieve alteration for specific collection
     * 
     * @since Release 1.0.0
	 * 
	 * @param string $cid - Collection Id
	 * @param string $state - Collection State (Initial/Last)
	 * 
	 * @return object
	 */
	public function fetchCollectionChanges(string $cid, string $state, string $scheme = 'I'): ?object {

        // execute command
        $cr = $this->RemoteCommonService->fetchFolderChanges($this->DataStore, $cid, $state, false, 512, $scheme);
		// return response
		return $cr;

    }

    /**
     * retrieve all collection items uuids from remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
	 * 
	 * @return array
	 */
	public function fetchCollectionItemsUUID(string $cid, bool $ctype = false): array {

        // define place holders
        $data = array();
        $offset = 0;

        do {
            // execute command
            $ro = $this->RemoteCommonService->fetchItemsIds($this->DataStore, $cid, $ctype, $offset);
            // validate response object
            if (isset($ro) && count($ro->Contact) > 0) {
                foreach ($ro->Contact as $entry) {
                    if ($entry->ExtendedProperty) {
                        $data[] = array('ID'=>$entry->ItemId->Id, 'UUID'=>$entry->ExtendedProperty[0]->Value);
                    }
                }
                $offset += count($ro->Contact);
            }
        }
        while (isset($ro) && count($ro->Contact) > 0);
        // return
		return $data;
    }

	/**
     * retrieve collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $iid - Collection Item ID
	 * 
	 * @return ContactObject
	 */
	public function fetchCollectionItem(string $iid): ?ContactObject {

        // construct identification object
        $io = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);
        // execute command
		$ro = $this->RemoteCommonService->fetchItem($this->DataStore, array($io), 'D', $this->constructDefaultItemProperties());
        // validate response
		if (isset($ro->Contact)) {
            // convert to contact object
            $co = $this->toContactObject($ro->Contact[0]);
            // retrieve attachment(s) from remote data store
			if (count($co->Attachments) > 0) {
				$co->Attachments = $this->fetchCollectionItemAttachment(array_column($co->Attachments, 'Id'));
			}
            // return object
		    return $co;
        } else {
            // return null
            return null;
        }

    }

	/**
     * find collection item by uuid in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $uuid -Collection Item UUID
	 * 
	 * @return ContactObject
	 */
	public function fetchCollectionItemByUUID(string $cid, string $uuid): ?ContactObject {

        // retrieve properties for a specific collection item
		$data = $this->RemoteCommonService->findItemByUUID($this->DataStore, $cid, $uuid, false, 'D', $this->constructDefaultItemProperties());
        // process response
		if (isset($data) && (count($data) > 0)) {
            // convert to contact object
            $co = $this->toContactObject($data[0]);
            // retrieve attachment(s) from remote data store
			if (count($co->Attachments) > 0) {
				$co->Attachments = $this->fetchCollectionItemAttachment(array_column($co->Attachments, 'Id'));
			}
            // return object
		    return $co;
        } else {
            return null;
        }
    }
    
	/**
     * create collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param ContactObject $so - Source Data
	 * 
	 * @return ContactObject
	 */
	public function createCollectionItem(string $cid, ContactObject $so): ?ContactObject {

        // construct request object
        $ro = new ContactItemType();
        // Label
        if (!empty($so->Label)) {
            $ro->DisplayName = $so->Label;
        }
        // Names
        if (isset($so->Name)) {
            // Last Name
            if (!empty($so->Name->Last)) {
                $ro->Surname = $so->Name->Last;
            }
            // First Name
            if (!empty($so->Name->First)) {
                $ro->GivenName = $so->Name->First;
            }
            // Other Name
            if (!empty($so->Name->Other)) {
                $ro->MiddleName = $so->Name->Other;
            }
            // Prefix
            if (!empty($so->Name->Prefix)) {
                $ro->ExtendedProperty[] = $this->createFieldExtendedByTag('0x3A45', 'String', $so->Name->Prefix);
            }
            // Suffix
            if (!empty($so->Name->Suffix)) {
                $ro->Generation = $so->Name->Suffix;
            }
            // Phonetic Last
            if (!empty($so->Name->PhoneticLast)) {
                $ro->PhoneticLastName = $so->Name->PhoneticLast;
            }
            // Phonetic First
            if (!empty($so->Name->PhoneticFirst)) {
                $ro->PhoneticFirstName = $so->Name->PhoneticFirst;
            }
            // Aliases
            if (!empty($so->Name->Aliases)) {
                $ro->NickName = $so->Name->Aliases;
            }
        }
        // Birth Day
        if (!empty($so->BirthDay)) {
            $ro->Birthday = $so->BirthDay->format('Y-m-d\TH:i:s\Z');
        }
        // Gender
        if (!empty($so->Gender)) {
            $ro->ExtendedProperty[] = $this->createFieldExtendedByTag('0x3A4D', 'String', $so->Gender);
        }
        // Partner
        if (!empty($so->Partner)) {
            $ro->SpouseName = $so->Partner;
        }
        // Anniversary Day
        if (!empty($so->AnniversaryDay)) {
            $ro->WeddingAnniversary = $so->AnniversaryDay->format('Y-m-d\TH:i:s\Z');
        }
        // Address(es)
        if (count($so->Address) > 0) {
            $types = array(
                'WORK' => true,
                'HOME' => true,
                'OTHER' => true
            );
            foreach ($so->Address as $entry) {
                if (isset($types[$entry->Type]) && $types[$entry->Type] == true) {
                    if (!isset($ro->PhysicalAddresses->Entry)) { $ro->PhysicalAddresses = new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(); }
                    $ro->PhysicalAddresses->Entry[] = new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                        $this->toAddressType($entry->Type),
                        $entry->Street,
                        $entry->Locality,
                        $entry->Region,
                        $entry->Code,
                        $entry->Country,
                    );
                    $types[$entry->Type] = false;
                }
            }
        }
        // Phone(s)
        if (count($so->Phone) > 0) {
            foreach ($so->Phone as $entry) {
                $type = $this->toTelType($entry->Type);
                if ($type && !empty($entry->Number)) {
                    if (!isset($ro->PhoneNumbers->Entry)) { $ro->PhoneNumbers = new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryType(); } 
                    $ro->PhoneNumbers->Entry[] = new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType(
                        $type, 
                        $entry->Number
                    );
                }
            }
        }
        // Email(s)
        if (count($so->Email) > 0) {
            $types = array(
                'WORK' => true,
                'HOME' => true,
                'OTHER' => true
            );
            foreach ($so->Email as $entry) {
                if (isset($types[$entry->Type]) && $types[$entry->Type] == true && !empty($entry->Address)) {
                    if (!isset($ro->EmailAddresses->Entry)) { $ro->EmailAddresses = new \OCA\EWS\Components\EWS\Type\EmailAddressDictionaryType(); }
                    $ro->EmailAddresses->Entry[] = new \OCA\EWS\Components\EWS\Type\EmailAddressDictionaryEntryType(
                        $this->toEmailType($entry->Type),
                        $entry->Address
                    );
                    $types[$entry->Type] = false;
                }
            }
        }
        // IMPP(s)
        if (count($so->IMPP) > 0) {
            // TODO: Add IMPP Code
        }
        // TimeZone
        if (!empty($so->TimeZone)) {
            // TODO: Add TimeZone Code
        }
        // Geolocation
        if (!empty($so->Geolocation)) {
            // TODO: Add Geolocation Code
        }
        // Manager Name
        if (!empty($so->Manager)) {
            $ro->Manager = $so->Manager;
        }
        // Assistant Name
        if (!empty($so->Assistant)) {
            $ro->AssistantName = $so->Assistant;
        }
        // Occupation
        if (isset($so->Occupation)) {
            if (!empty($so->Occupation->Organization)) {
                $ro->CompanyName = $so->Occupation->Organization;
            }
            if (!empty($so->Occupation->Department)) {
                $ro->Department = $so->Occupation->Department;
            }
            if (!empty($so->Occupation->Title)) {
                $ro->JobTitle = $so->Occupation->Title;
            }
            if (!empty($so->Occupation->Role)) {
                $ro->Profession = $so->Occupation->Role;
            }
        }
        // Tag(s)
        if (count($so->Tags) > 0) {
            $ro->Categories = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType;
            foreach ($so->Tags as $entry) {
                $ro->Categories->String[] = $entry;
            }
        }
        // Notes
        if (!empty($so->Notes)) {
            $ro->Body = new \OCA\EWS\Components\EWS\Type\BodyType(
                'Text',
                $so->Notes
            );
        }
        // UID
        if (!empty($so->UID)) {
            $ro->ExtendedProperty[] = $this->createFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UID);
        }
        // set the "file as" mapping to "LastFirstCompany"
        $ro->FileAsMapping = 'LastFirstCompany';
        // execute command
        $rs = $this->RemoteCommonService->createItem($this->DataStore, $cid, $ro);

        // Photo
        // TODO: Remove after testing
        /*
        if ($result->ItemId) {
            if ($so->Photo->Data) {
                $a = new \OCA\EWS\Components\EWS\Type\FileAttachmentType();
                $a->IsInline = false;
                $a->IsContactPhoto = true;
                $a->Name = 'ContactPicture.' . \OCA\EWS\Utile\MIME::toExtension($so->Attachments[$so->Photo->Data]->Type);
                $a->ContentId = $so->Attachments[$so->Photo->Data]->Name;
                $a->ContentType = $so->Attachments[$so->Photo->Data]->Type;

                if ($so->Photo->Type == 'uri') {
                    // TODO: Download And Save Image
                } elseif ($so->Photo->Type == 'data') {
                    if (isset($so->Attachments[$so->Photo->Data])) {
                        switch ($so->Attachments[$so->Photo->Data]->Encoding) {
                            case 'B':
                                $a->Content = $so->Attachments[$so->Photo->Data];
                                $this->createAttachment($this->DataStore, $result->ItemId->Id, array($a));
                                break;
                            case 'B64':
                                $a->Content = base64_decode($so->Attachments[$so->Photo->Data]->Data);
                                $this->createAttachment($this->DataStore, $result->ItemId->Id, array($a));
                                break;
                        }
                    }
                }
            }
        }
        */

        // process response
        if ($rs->ItemId) {
			$co = clone $so;
			$co->ID = $rs->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($co->Attachments) > 0) {
				// create attachments in remote data store
				$co->Attachments = $this->createCollectionItemAttachment($co->ID, $co->Attachments);
				$co->State = $co->Attachments[0]->AffiliateState;
			}
            return $co;
        } else {
            return null;
        }

    }

     /**
     * update collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
     * @param ContactObject $so - Source Data
	 * 
	 * @return ContactObject
	 */
	public function updateCollectionItem(string $cid, string $iid, ContactObject $so): ?ContactObject {

        // request modifications array
        $rm = array();
        // request deletions array
        $rd = array();
        // Label
        if (!empty($so->Label)) {
            $rm[] = $this->updateFieldUnindexed('contacts:DisplayName', 'DisplayName', $so->Label);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:DisplayName');
        }
        // Names
        if (isset($so->Name)) {
            // Last Name
            if (!empty($so->Name->Last)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Surname', 'Surname', $so->Name->Last);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Surname');
            }
            // First Name
            if (!empty($so->Name->First)) {
                $rm[] = $this->updateFieldUnindexed('contacts:GivenName', 'GivenName', $so->Name->First);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:GivenName');
            }
            // Other Name
            if (!empty($so->Name->Other)) {
                $rm[] = $this->updateFieldUnindexed('contacts:MiddleName', 'MiddleName', $so->Name->Other);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:MiddleName');
            }
            // Prefix
            if (!empty($so->Name->Prefix)) {
                $rm[] = $this->updateFieldExtendedByTag('0x3A45', 'String', $so->Name->Prefix);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('0x3A45', 'String');
            }
            // Suffix
            if (!empty($so->Name->Suffix)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Generation', 'Generation', $so->Name->Suffix);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Generation');
            }
            // Phonetic Last
            if (!empty($so->Name->PhoneticLast)) {
                $rm[] = $this->updateFieldExtendedByTag('0x802D', 'String', $so->Name->PhoneticLast);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('0x802D', 'String');
            }
            // Phonetic First
            if (!empty($so->Name->PhoneticFirst)) {
                $rm[] = $this->updateFieldExtendedByTag('0x802C', 'String', $so->Name->PhoneticFirst);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('0x802C', 'String');
            }
            // Aliases
            if (!empty($so->Name->Aliases)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Nickname', 'Nickname', $so->Name->Aliases);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Nickname');
            }
        }
        // Birth Day
        if (!empty($so->BirthDay)) {
            $rm[] = $this->updateFieldUnindexed('contacts:Birthday', 'Birthday', $so->BirthDay->format('Y-m-d\TH:i:s\Z'));
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:Birthday');
        }
        // Gender
        if (!empty($so->Gender)) {
            $rm[] = $this->updateFieldExtendedByTag('0x3A4D', 'String', $so->Gender);
        }
        else {
            $rd[] = $this->deleteFieldExtendedByTag('0x3A4D', 'String');
        }
        // Partner
        if (!empty($so->Partner)) {
            $rm[] = $this->updateFieldUnindexed('contacts:SpouseName', 'SpouseName', $so->Partner);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:SpouseName');
        }
        // Anniversary Day
        if (!empty($so->AnniversaryDay)) {
            $rm[] = $this->updateFieldUnindexed('contacts:WeddingAnniversary', 'WeddingAnniversary', $so->AnniversaryDay->format('Y-m-d\TH:i:s\Z'));
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:WeddingAnniversary');
        }
        // Address(es)
        $types = array(
            'Business' => true,
            'Home' => true,
            'Other' => true
        );
        // update address
        if (count($so->Address) > 0) {
            foreach ($so->Address as $entry) {
                // convert address type
                $type = $this->toAddressType($entry->Type);
                // process if index not used already
                if (isset($types[$type]) && $types[$type] == true) {
                    // street
                    if (!empty($entry->Street)) {
                        $rm[] = $this->updateFieldIndexed(
                            'contacts:PhysicalAddress:Street',
                            $type,
                            'PhysicalAddresses',
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(),
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                                $type,
                                $entry->Street,
                                null,
                                null,
                                null,
                                null
                            )
                        );
                    }
                    else {
                        $rd[] = $this->deleteFieldIndexed(
                            'contacts:PhysicalAddress:Street',
                            $type
                        );
                    }
                    // locality
                    if (!empty($entry->Locality)) {    
                        $rm[] = $this->updateFieldIndexed(
                            'contacts:PhysicalAddress:City',
                            $type,
                            'PhysicalAddresses',
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(),
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                                $type,
                                null,
                                $entry->Locality,
                                null,
                                null,
                                null
                            )
                        );
                    }
                    else {
                        $rd[] = $this->deleteFieldIndexed(
                            'contacts:PhysicalAddress:City',
                            $type
                        );
                    }
                    // region
                    if (!empty($entry->Region)) {  
                        $rm[] = $this->updateFieldIndexed(
                            'contacts:PhysicalAddress:State',
                            $type,
                            'PhysicalAddresses',
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(),
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                                $type,
                                null,
                                null,
                                $entry->Region,
                                null,
                                null
                            )
                        );
                    }
                    else {
                        $rd[] = $this->deleteFieldIndexed(
                            'contacts:PhysicalAddress:State',
                            $type
                        );
                    }
                    // code
                    if (!empty($entry->Code)) {
                        $rm[] = $this->updateFieldIndexed(
                            'contacts:PhysicalAddress:PostalCode',
                            $type,
                            'PhysicalAddresses',
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(),
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                                $type,
                                null,
                                null,
                                null,
                                $entry->Code,
                                null
                            )
                        );
                    }
                    else {
                        $rd[] = $this->deleteFieldIndexed(
                            'contacts:PhysicalAddress:PostalCode',
                            $type
                        );
                    }
                    // country
                    if (!empty($entry->Country)) {
                        $rm[] = $this->updateFieldIndexed(
                            'contacts:PhysicalAddress:CountryOrRegion',
                            $type,
                            'PhysicalAddresses',
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryType(),
                            new \OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType(
                                $type,
                                null,
                                null,
                                null,
                                null,
                                $entry->Country
                            )
                        );
                    }
                    else {
                        $rd[] = $this->deleteFieldIndexed(
                            'contacts:PhysicalAddress:CountryOrRegion',
                            $type
                        );
                    }
                    $types[$type] = false;
                }
            }
        }
        // delete address
        foreach ($types as $type => $status) {
            if ($status) {
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhysicalAddress:Street',
                    $type
                );
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhysicalAddress:City',
                    $type
                );
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhysicalAddress:State',
                    $type
                );
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhysicalAddress:PostalCode',
                    $type
                );
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhysicalAddress:CountryOrRegion',
                    $type
                );
            }
        }
        // Phone(s)
        $types = array(
            'BusinessPhone' => true,
            'BusinessPhone2' => true,
            'BusinessFax' => true,
            'HomePhone' => true,
            'HomePhone2' => true,
            'HomeFax' => true,
            'CarPhone' => true,
            'Isdn' => true,
            'MobilePhone' => true,
            'Pager' => true,
            'OtherTelephone' => true,
            'OtherFax' => true,
        );
        // update phone
        if (count($so->Phone) > 0) {
            foreach ($so->Phone as $entry) {
                // convert email type
                $type = $this->toTelType($entry->Type);
                // process if index not used already
                if (isset($types[$type]) && $types[$type] == true && !empty($entry->Number)) {
                    $rm[] = $this->updateFieldIndexed(
                        'contacts:PhoneNumber',
                        $type,
                        'PhoneNumbers',
                        new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryType(),
                        new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType(
                            $type, 
                            $entry->Number
                        )
                    );
                    $types[$type] = false;
                }
            }
        }
        // delete phone
        foreach ($types as $type => $status) {
            if ($status) {
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhoneNumber',
                    $type
                );
            }
        }
        // Email(s)
        $types = array(
            'EmailAddress1' => true,
            'EmailAddress2' => true,
            'EmailAddress3' => true
        );
        // update email
        if (count($so->Email) > 0) {
            foreach ($so->Email as $entry) {
                // convert email type
                $type = $this->toEmailType($entry->Type);
                // process if index not used already
                if (isset($types[$type]) && $types[$type] == true && !empty($entry->Address)) {
                    $rm[] = $this->updateFieldIndexed(
                        'contacts:EmailAddress',
                        $type,
                        'EmailAddresses',
                        new \OCA\EWS\Components\EWS\Type\EmailAddressDictionaryType(),
                        new \OCA\EWS\Components\EWS\Type\EmailAddressDictionaryEntryType(
                            $type,
                            $entry->Address
                        )
                    );
                    $types[$type] = false;
                }
            }
        }
        // delete email
        foreach ($types as $type => $status) {
            if ($status) {
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:EmailAddress',
                    $type
                );
            }
        }
        // TimeZone
        if (!empty($so->TimeZone)) {
            // TODO: Add TimeZone Code
        }
        // Geolocation
        if (!empty($so->Geolocation)) {
            // TODO: Add Geolocation Code
        }
        // Manager Name
        if (!empty($so->Manager)) {
            $rm[] = $this->updateFieldUnindexed('contacts:Manager', 'Manager', $so->Manager);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:Manager');
        }
        // Assistant Name
        if (!empty($so->Assistant)) {
            $rm[] = $this->updateFieldUnindexed('contacts:AssistantName', 'AssistantName', $so->Assistant);
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:AssistantName');
        }
        // Occupation
        if (isset($so->Occupation)) {
            // Occupation - Name
            if (!empty($so->Occupation->Organization)) {
                $rm[] = $this->updateFieldUnindexed('contacts:CompanyName', 'CompanyName', $so->Occupation->Organization);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:CompanyName');
            }
            // Occupation - Department
            if (!empty($so->Occupation->Department)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Department', 'Department', $so->Occupation->Department);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Department');
            }
            // Occupation - Title
            if (!empty($so->Occupation->Title)) {
                $rm[] = $this->updateFieldUnindexed('contacts:JobTitle', 'JobTitle', $so->Occupation->Title);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:JobTitle');
            }
            // Occupation - Role
            if (!empty($so->Occupation->Role)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Profession', 'Profession', $so->Occupation->Role);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Profession');
            }
        }
		// Tag(s)
		if (count($so->Tags) > 0) {
			$f = new \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType;
			foreach ($so->Tags as $entry) {
				$f->String[] = $entry;
			}
			$rm[] = $this->updateFieldUnindexed('item:Categories', 'Categories', $f);
		}
		else {
			$rd[] = $this->deleteFieldUnindexed('item:Categories');
		}
        // Notes
        if (!empty($so->Notes)) {
            $rm[] = $this->updateFieldUnindexed(
                'item:Body',
                'Body', 
                new \OCA\EWS\Components\EWS\Type\BodyType(
                    'Text',
                    $so->Notes
            ));
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('item:Body');
        }
        // UID
        if (!empty($so->UID)) {
            $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $so->UID);
        }
        else {
            $rd[] = $this->deleteFieldExtendedByName('PublicStrings', 'DAV:uid', 'String');
        }
        // execute command
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, $rd);
        // process response
        if ($rs->ItemId) {
			$co = clone $so;
			$co->ID = $rs->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->ItemId->ChangeKey;
			// deposit attachment(s)
			if (count($so->Attachments) > 0) {
				// create attachments in remote data store
				$co->Attachments = $this->createCollectionItemAttachment($co->ID, $co->Attachments);
				$co->State = $co->Attachments[0]->AffiliateState;
			}
            return $co;
        } else {
            return null;
        }
        
    }

    /**
     * update collection item with uuid in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
     * @param string $cid - Collection Item UUID
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItemUUID(string $cid, string $iid, string $uuid): ?object {
		// request modifications array
        $rm = array();
        // construct update command object
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, $rm, null);
        // return response
        if ($rs->ItemId) {
            return (object) array('ID' => $rs->ItemId->Id, 'UID' => $uuid, 'State' => $rs->ItemId->ChangeKey);
        } else {
            return null;
        }
    }
    
    /**
     * delete collection item in remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $iid - Item ID
	 * 
	 * @return bool Ture - successfully destroyed / False - failed to destory
	 */
    public function deleteCollectionItem(string $iid): bool {
        // create object
        $o = new \OCA\EWS\Components\EWS\Type\ItemIdType($iid);

        $rs = $this->RemoteCommonService->deleteItem($this->DataStore, array($o));

        if ($rs) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * retrieve collection item attachment from remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $aid - Attachment ID
	 * 
	 * @return array
	 */
	public function fetchCollectionItemAttachment(array $batch): array {

		// check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
		// retrieve attachments
		$rs = $this->RemoteCommonService->fetchAttachment($this->DataStore, $batch);
		// construct response collection place holder
		$rc = array();
		// check for response
		if (isset($rs)) {
			// process collection of objects
			foreach($rs as $entry) {
				if (!isset($entry->ContentType) || $entry->ContentType == 'application/octet-stream') {
					$type = \OCA\EWS\Utile\MIME::fromFileName($entry->Name);
				} else {
					$type = $entry->ContentType;
				}
                if ($entry->IsContactPhoto || str_contains($entry->Name, 'ContactPicture')) {
                    $flag = 'CP';
                }
                else {
                    $flag = null;
                }
				// insert attachment object in response collection
				$rc[] = new ContactAttachmentObject(
					$entry->AttachmentId->Id, 
					$entry->Name,
					$type,
					'B',
                    $flag,
					$entry->Size,
					$entry->Content
				);
			}
		}
		// return response collection
		return $rc;

    }

    /**
     * create collection item attachment in remote storage
     * 
     * @since Release 1.0.0
     * 
	 * @param string $aid - Affiliation ID
     * @param array $sc - Collection of ContactAttachmentObject(S)
	 * 
	 * @return array
	 */
	public function createCollectionItemAttachment(string $aid, array $batch): array {

		// check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
		// construct command collection place holder
		$cc = array();
		// process batch
		foreach ($batch as $key => $entry) {
			// construct command object
			$co = new \OCA\EWS\Components\EWS\Type\FileAttachmentType();
			$co->IsInline = false;
			$co->ContentId = $entry->Name;
			$co->ContentType = $entry->Type;
            $co->Name = $entry->Name;
			$co->Size = $entry->Size;

            if ($entry->Flag == 'CP') {
                $co->IsContactPhoto = true;
            }
            else {
                $co->IsContactPhoto = false;
            }
            
			switch ($entry->Encoding) {
				case 'B':
					$co->Content = $entry->Data;
					break;
				case 'B64':
					$co->Content = base64_decode($entry->Data);
					break;
			}
			// insert command object in to collection
			$cc[] = $co;
		}
		// execute command(s)
		$rs = $this->RemoteCommonService->createAttachment($this->DataStore, $aid, $cc);
		// construct results collection place holder
		$rc = array();
		// check for response
		if (isset($rs)) {
			// process collection of objects
			foreach($rs as $key => $entry) {
				$ro = $batch[$key];
				$ro->Id = $entry->AttachmentId->Id;
				$ro->Data = null;
				$ro->AffiliateId = $entry->AttachmentId->RootItemId;
				$ro->AffiliateState = $entry->AttachmentId->RootItemChangeKey;
				$rc[] = $ro;
			}

        }
		// return response collection
		return $rc;
    }

    /**
     * delete collection item attachment from remote storage
     * 
     * @since Release 1.0.0
     * 
     * @param string $aid - Attachment ID
	 * 
	 * @return array
	 */
	public function deleteCollectionItemAttachment(array $batch): array {

		// check to for entries in batch collection
        if (count($batch) == 0) {
            return array();
        }
		// execute command
		$data = $this->RemoteCommonService->deleteAttachment($this->DataStore, $batch);

		return $data;

    }

    /**
     * construct collection of default remote collection properties 
     * 
     * @since Release 1.0.0
	 * 
	 * @return object
	 */
    private function constructDefaultCollectionProperties(): object {

		// construct properties array
		if (!isset($this->DefaultCollectionProperties)) {
			$p = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:FolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:FolderClass');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:ParentFolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:DisplayName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('folder:TotalCount');


			$this->DefaultCollectionProperties = $p;
		}

		return $this->DefaultCollectionProperties;

	}

    /**
     * construct collection of default remote object properties 
     * 
     * @since Release 1.0.0
	 * 
	 * @return object
	 */
    private function constructDefaultItemProperties(): object {

		// construct properties array
		if (!isset($this->DefaultItemProperties)) {
			$p = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ItemId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:ParentFolderId');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeCreated');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:DateTimeSent');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:LastModifiedTime');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Categories');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Body');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Attachments');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:DisplayName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:CompleteName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:PhoneticLastName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:PhoneticFirstName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Birthday');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:SpouseName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:WeddingAnniversary');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:PhysicalAddresses');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:PhoneNumbers');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:EmailAddresses');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:ImAddresses');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:CompanyName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Manager');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:AssistantName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Department');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:JobTitle');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Profession');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:OfficeLocation');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:HasPicture');
            // Name Prefix
            /*
            $p->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                '0x3A45',
                'String'
            );
            // Yomi/Phonetic Last Name
            $p->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                '0x802D',
                'String'
            );
            // Yomi/Phonetic Last Name
            $p->ExtendedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                '0x802C',
                'String'
            );
            */
			$this->DefaultItemProperties = $p;
		}

		return $this->DefaultItemProperties;

	}
    
    /**
     * construct collection item unindexed property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
     * @param string $name - property name
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldUnindexed(string $uri, string $name, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($uri);
        // create field contact object
        $o->Contact = new \OCA\EWS\Components\EWS\Type\ContactItemType();
        $o->Contact->$name = $value;
        // return object
        return $o;
    }

    /**
     * construct collection item unindexed property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldUnindexed(string $uri): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->FieldURI = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($uri);
        // return object
        return $o;
    }

    /**
     * construct collection item indexed property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $uri - property uri
     * @param string $index - property index
     * @param string $name - property name
     * @param string $dictionary - property dictionary object
     * @param string $entry - property entry object
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldIndexed(string $uri, string $index, string $name, mixed $dictionary, mixed $entry): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->IndexedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($uri, $index);
        // create field contact object
        $o->Contact = new \OCA\EWS\Components\EWS\Type\ContactItemType();
        $o->Contact->$name = $dictionary;
        $o->Contact->$name->Entry = $entry;
        // return object
        return $o;
    }

    /**
     * construct collection item indexed property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldIndexed(string $uri, string $index): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->IndexedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($uri, $index);
        // return object
        return $o;
    }

    /**
     * construct collection item extended property create command
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByName(string $collection, string $name, string $type, mixed $value): object {
        // create extended field object
        $o = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                null,
                $name,
                null,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByName(string $collection, string $name, string $type, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            null,
            $name,
            null,
            $type
        );
        // create field contact object
        $o->Contact = new \OCA\EWS\Components\EWS\Type\ContactItemType();
        $o->Contact->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                null,
                $name,
                null,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property delete 
     * 
     * @since Release 1.0.0
     * 
     * @param string $collection - property collection
     * @param string $name - property name
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByName(string $collection, string $name, string $type): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            null,
            $name,
            null,
            $type
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property create command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property create command
	 */
    public function createFieldExtendedByTag(string $tag, string $type, mixed $value): object {
        // create extended field object
        $o = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                $tag,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property update command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
     * @param string $value - property value
	 * 
	 * @return object collection item property update command
	 */
    public function updateFieldExtendedByTag(string $tag, string $type, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            null,
            null,
            null,
            null,
            $tag,
            $type
        );
        // create field contact object
        $o->Contact = new \OCA\EWS\Components\EWS\Type\ContactItemType();
        $o->Contact->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                null,
                null,
                null,
                null,
                $tag,
                $type
            ),
            $value
        );
        // return object
        return $o;
    }

    /**
     * construct collection item extended property delete command
     * 
     * @since Release 1.0.0
     * 
     * @param string $tag - property tag
     * @param string $type - property type
	 * 
	 * @return object collection item property delete command
	 */
    public function deleteFieldExtendedByTag(string $tag, string $type): object {
        // construct field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            null,
            null,
            null,
            null,
            $tag,
            $type
        );
        // return object
        return $o;
    }

    /**
     * convert remote ContactItemType object to contact object
     * 
     * @since Release 1.0.0
     * 
	 * @param ContactItemType $data - item as vcard object
	 * 
	 * @return ContactObject item as contact object
	 */
	public function toContactObject(ContactItemType $data): ContactObject {

		// create object
		$o = new ContactObject();
        // ID + State
        if (isset($data->ItemId)) {
            $o->ID = $data->ItemId->Id;
            $o->State = $data->ItemId->ChangeKey;
        }
        // Collection ID
        if (isset($data->ParentFolderId)) {
            $o->CID = $data->ParentFolderId->Id;
        }
        // Creation Date
        if (!empty($data->DateTimeCreated)) {
            $o->CreatedOn = new DateTime($data->DateTimeCreated);
        }
        // Modification Date
        if (!empty($data->DateTimeSent)) {
            $o->ModifiedOn = new DateTime($data->DateTimeSent);
        }
        if (!empty($data->LastModifiedTime)) {
            $o->ModifiedOn = new DateTime($data->LastModifiedTime);
        }
        // Label
        if (!empty($data->DisplayName)) {
            $o->Label = $data->DisplayName;
        }
		// Name
        if (isset($data->CompleteName)) {
            $o->Name->Last = $data->CompleteName->LastName;
            $o->Name->First = $data->CompleteName->FirstName;
            $o->Name->Other = $data->CompleteName->MiddleName;
            $o->Name->Prefix = $data->CompleteName->Title;
            $o->Name->Suffix = $data->CompleteName->Suffix;
            $o->Name->PhoneticLast = $data->CompleteName->YomiLastName;
            $o->Name->PhoneticFirst = $data->CompleteName->YomiLastName;
            $o->Name->Aliases = $data->CompleteName->Nickname;
        }
        // Phonetic Last Name
        if (!empty($data->PhoneticLastName)) {
            $o->Name->PhoneticLast =  new DateTime($data->PhoneticLastName);
        }
        // Phonetic First Name
        if (!empty($data->PhoneticFirstName)) {
            $o->Name->PhoneticFirst =  new DateTime($data->PhoneticFirstName);
        }
        // Birth Day
        if (!empty($data->Birthday)) {
            $o->BirthDay =  new DateTime($data->Birthday);
        }
        // Partner
        if (!empty($data->SpouseName)) {
            $o->Partner = $data->SpouseName;
        }
        // Anniversary Day
        if (!empty($data->WeddingAnniversary)) {
            $o->AnniversaryDay =  new DateTime($data->WeddingAnniversary);
        }
        // Address(es)
        if (isset($data->PhysicalAddresses)) {
            foreach($data->PhysicalAddresses->Entry as $entry) {
                $o->addAddress(
                    $entry->Key,
                    $entry->Street,
                    $entry->City,
                    $entry->State,
                    $entry->PostalCode,
                    $entry->CountryOrRegion
                );
            }
        }
        // Phone(s)
        if (isset($data->PhoneNumbers)) {
            foreach($data->PhoneNumbers->Entry as $entry) {
                $t = $this->fromTelType($entry->Key); 
                if ($t) {
                    $o->addPhone(
                        $t,
                        null,
                        $entry->_
                    );
                }
            }
        }
        // Email(s)
        if (isset($data->EmailAddresses)) {
            foreach($data->EmailAddresses->Entry as $entry) {
                $t = $this->fromEmailType($entry->Key);
                if ($t) {
                    $o->addEmail(
                        $t, 
                        $entry->_
                    );
                }
            }
        }
        // IMPP(s)
        if (isset($data->ImAddresses)) {
            foreach($data->ImAddresses->Entry as $entry) {
                $o->addIMPP(
                    $entry->Key, 
                    $entry->_
                );
            }
        }
        // Manager Name
        if (!empty($data->Manager)) {
            $o->Name->Manager =  $data->Manager;
        }
        // Assistant Name
        if (!empty($data->AssistantName)) {
            $o->Name->Assistant =  $data->AssistantName;
        }
        // Occupation Organization
        if (!empty($data->CompanyName)) {
            $o->Occupation->Organization = $data->CompanyName;
        }
        // Occupation Department
        if (!empty($data->Department)) {
            $o->Occupation->Department = $data->Department;
        }
        // Occupation Title
        if (!empty($data->JobTitle)) {
            $o->Occupation->Title = $data->JobTitle;
        }
        // Occupation Role
        if (!empty($data->Profession)) {
            $o->Occupation->Role = $data->Profession;
        }
        // Occupation Location
        if (!empty($data->OfficeLocation)) {
            $o->Occupation->Location = $data->OfficeLocation;
        }
        // Relation
        //if ($data->RELATED) {
        //    $this->Relation = new ContactRelationObject($data->RELATED->parameters()['TYPE']->getValue(), $data->RELATED->getValue());
        //}
        // Tag(s)
        if (isset($data->Categories)) {
            foreach($data->Categories->String as $entry) {
                $o->addTag($entry);
            }
        }
        // Notes
        if (!empty($data->Body)) {
            $o->Notes = $data->Body->_;
        }
        // Sound
        //if ($data->SOUND) {
        //    $this->Sound = $data->SOUND->getValue();
        //}
        // URL / Website
        if (isset($o->URL)) {
            $this->URI = $data->URL->getValue();
        }

        // Attachment(s)
        if (isset($data->Attachments)) {
            foreach($data->Attachments->FileAttachment as $entry) {
                // evaluate mime type
                if ($entry->ContentType == 'application/octet-stream') {
                    $type = \OCA\EWS\Utile\MIME::fromFileName($entry->Name);
                } else {
                    $type = $entry->ContentType;
                }
                // evaluate attachemnt type
                if ($entry->IsContactPhoto || str_contains($entry->Name, 'ContactPicture')) {
                    $flag = 'CP';
                    $o->Photo->Type = 'data';
                    $o->Photo->Data = $entry->AttachmentId->Id;
                }
                else {
                    $flag = null;
                }
                $o->addAttachment(
					$entry->AttachmentId->Id, 
					$entry->Name,
					$type,
					'B',
                    $flag,
					$entry->Size,
					$entry->Content
				);
            }
        }

        // UID / Dates
        if (isset($data->ExtendedProperty)) {
            foreach ($data->ExtendedProperty as $entry) {
                switch ($entry->ExtendedFieldURI->PropertyName) {
                    case 'DAV:uid': // UUID
                        $o->UID = $entry->Value;
                        break;
                }
                switch ($entry->ExtendedFieldURI->PropertyTag) {
                    case '0x3007': // Created Date/Time
                        $o->CreatedOn = new DateTime($entry->Value);
                        break;
                    case '0x3008': // Modified Date/Time
                        $o->ModifiedOn = new DateTime($entry->Value);
                        break;
                    case '0x3A45': // Yomi / Name Prefix
                        break;
                    case '0x802D': // Yomi / Phonetic Last Name
                        break;
                    case '0x802C': // Yomi / Phonetic First Name
                        break;
                }
            }
        }

		return $o;

    }

    /**
     * convert remote email type to contact object type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - remote email type
	 * 
	 * @return string|null contact object email type
	 */
    private function fromEmailType(string $type): ?string {

        // type conversion reference
        $_tm = array(
			'EmailAddress1' => 'WORK',
			'EmailAddress2' => 'HOME',
			'EmailAddress3' => 'OTHER'
		);
        // evaluate if type value exists
		if (isset($_tm[$type])) {
			// return converted type value
			return $_tm[$type];
		} else {
            // return default type value
			return null;
		}

    }

    /**
     * convert local email type to remote type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - contact object email type
	 * 
	 * @return string|null remote email type
	 */
    private function toEmailType(string $type): string {

        // type conversion reference
        $_tm = array(
			'WORK' => 'EmailAddress1',
			'HOME' => 'EmailAddress2',
			'OTHER' => 'EmailAddress3'
		);
        // evaluate if type value exists
		if (isset($_tm[$type])) {
			// return converted type value
			return $_tm[$type];
		} else {
            // return default type value
			return '';
		}

    }

    /**
     * convert remote telephone type to contact object type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - remote telephone type
	 * 
	 * @return string|null contact object telephone type
	 */
    private function fromTelType(string $type): ?string {
        switch ($type) {
            case 'BusinessPhone':
                return 'WORK,VOICE,1';
                break;
            case 'BusinessPhone2':
                return 'WORK,VOICE,2';
                break;
            case 'BusinessFax':
                return 'WORK,FAX,1';
                break;
            case 'HomePhone':
                return 'HOME,VOICE,1';
                break;
            case 'HomePhone2':
                return 'HOME,VOICE,2';
                break;
            case 'HomeFax':
                return 'HOME,FAX,1';
                break;
            case 'AssistantPhone':
                return null;
                break;
            case 'Callback':
                return null;
                break;
            case 'CarPhone':
                return 'CAR';
                break;
            case 'CompanyMainPhone':
                return null;
                break;
            case 'Isdn':
                return 'ISDN';
                break;
            case 'MobilePhone':
                return 'CELL';
                break;
            case 'OtherFax':
                return 'OTHER,FAX,1';
                break;
            case 'OtherTelephone':
                return 'OTHER,VOICE,1';
                break;
            case 'Pager':
                return 'PAGER';
                break;
            case 'PrimaryPhone':
                return null;
                break;
            case 'RadioPhone':
                return null;
                break;
            case 'Telex':
                return null;
                break;
            case 'TtyTddPhone':
                return null;
                break;
        }
    }

    /**
     * convert local telephone type to remote type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - contact object telephone type
	 * 
	 * @return string|null remote telephone type
	 */
    private function toTelType(string $type): ?string {
        $parts = explode(",", $type);
        $part2 = false;
        $part3 = false;

        switch ($parts[0]) {
            case 'WORK':
                $type = "Business";
                $part2 = true;
                $part3 = true;
                break;
            case 'HOME':
                $type = "Home";
                $part2 = true;
                $part3 = true;
                break;
            case 'OTHER':
                $type = "Other";
                $part2 = true;
                $part3 = true;
                break;
            case 'CELL':
                $type = "MobilePhone";
                $part2 = false;
                $part3 = false;
                break;
            case 'CAR':
                $type = "CarPhone";
                $part2 = false;
                $part3 = false;
                break;
            case 'PAGER':
                $type = "Pager";
                $part2 = false;
                $part3 = false;
                break;
            case 'ISDN':
                $type = "Isdn";
                $part2 = false;
                $part3 = false;
                break;
            default:
                $type = null;
                $part2 = false;
                $part3 = false;
                break;
        }

        if ($part2) {
            switch ($parts[1]) {
                case 'VOICE':
                    $type .= "Phone";
                    break;
                case 'FAX':
                    $type .= "Fax";
                    break;
                default:
                    $part3 = false;
                    $type = null;
                    break;
            }
        }

        if ($part3 && isset($parts[2])) {
            switch ($parts[2]) {
                case '0':
                case '1':
                    $type .= '';
                    break;
                case '2':
                    $type .= '2';
                    break;
                default:
                    $type = null;
                    break;
            }
        }

        return $type;
    }

    /**
     * convert remote address type to contact object type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - remote address type
	 * 
	 * @return string|null contact object address type
	 */
    private function fromAddressType(string $type): ?string {

        // type conversion reference
        $_tm = array(
			'Business' => 'WORK',
			'Home' => 'HOME',
			'Other' => 'OTHER'
		);
        // evaluate if type value exists
		if (isset($_tm[$type])) {
			// return converted type value
			return $_tm[$type];
		} else {
            // return default type value
			return null;
		}

    }

    /**
     * convert local address type to remote type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $type - contact object address type
	 * 
	 * @return string|null remote address type
	 */
    private function toAddressType(string $type): string {

        // type conversion reference
        $_tm = array(
			'WORK' => 'Business',
			'HOME' => 'Home',
			'OTHER' => 'Other'
		);
        // evaluate if type value exists
		if (isset($_tm[$type])) {
			// return converted type value
			return $_tm[$type];
		} else {
            // return default type value
			return '';
		}

    }

}
