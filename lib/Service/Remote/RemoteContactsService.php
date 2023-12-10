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
	private ?EWSClient $DataStore = null;
    /**
	 * @var Object
	 */
	private $Configuration;
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

    public function configure($configuration, EWSClient $DataStore) : void {
		
		// assign configuration
		$this->Configuration = $configuration;
		// assign remote data store
		$this->DataStore = $DataStore;
		
	}

	/**
	 * retrieve list of collections in remote storage
     * 
     * @since Release 1.0.0
	 *
     * @param string $source		folder source (U - User Folders, P - Public Folders)
	 * @param string $prefixName	string to append to folder name
     * 
	 * @return array of collections and properties
	 */
	public function listCollections(string $source = 'U', string $prefixName = ''): array {

		// execute command
		$cr = $this->RemoteCommonService->fetchFoldersByType($this->DataStore, 'IPF.Contact', 'I', $this->constructDefaultCollectionProperties(), $source);
        // process response
		$cl = array();
		if (isset($cr)) {
			foreach ($cr->ContactsFolder as $folder) {
				$cl[] = array('id'=>$folder->FolderId->Id, 'name'=>$prefixName . $folder->DisplayName,'count'=>$folder->TotalCount);
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
		$ro = $this->RemoteCommonService->findItemByUUID($this->DataStore, $cid, $uuid, false, 'D', $this->constructDefaultItemProperties());
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
                $ro->ExtendedProperty[] = $this->createFieldExtendedByTag('14917', 'String', $so->Name->Prefix);
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
            $ro->Birthday = $so->BirthDay->format('Y-m-d\TH:i:s');
        }
        // Gender
        if (!empty($so->Gender)) {
            $ro->ExtendedProperty[] = $this->createFieldExtendedByTag('14925', 'String', $so->Gender);
        }
        // Partner
        if (!empty($so->Partner)) {
            $ro->SpouseName = $so->Partner;
        }
        // Anniversary Day
        if (!empty($so->AnniversaryDay)) {
            $ro->WeddingAnniversary = $so->AnniversaryDay->format('Y-m-d\TH:i:s');
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
            $types = [
                'BusinessPhone' => ['Max' => 2, 'Count' => 1],
                'BusinessFax' => ['Max' => 1, 'Count' => 1],
                'HomePhone' => ['Max' => 2, 'Count' => 1],
                'HomeFax' => ['Max' => 1, 'Count' => 1],
                'OtherTelephone' => ['Max' => 1, 'Count' => 1],
                'OtherFax' => ['Max' => 1, 'Count' => 1],
                'MobilePhone' => ['Max' => 1, 'Count' => 1],
                'CarPhone' => ['Max' => 1, 'Count' => 1],
                'Pager' => ['Max' => 1, 'Count' => 1],
                'Isdn' => ['Max' => 1, 'Count' => 1],
                'AssistantPhone' => ['Max' => 1, 'Count' => 1],
                'Callback' => ['Max' => 1, 'Count' => 1],
                'CompanyMainPhone' => ['Max' => 1, 'Count' => 1],
                'PrimaryPhone' => ['Max' => 1, 'Count' => 1],
                'RadioPhone' => ['Max' => 1, 'Count' => 1],
                'Telex' => ['Max' => 1, 'Count' => 1],
                'TtyTddPhone' => ['Max' => 1, 'Count' => 1]
            ];
            foreach ($so->Phone as $entry) {
                // convert primary and secondary type to single type
                $type = $this->toPhoneType($entry->Type, $entry->SubType);
                // evaluate if type was converted
                $tc = (isset($types[$type])) ? $types[$type] : null;
                // if type is available and if number exists
                if (isset($tc) && ($tc['Count'] <= $tc['Max']) && !empty($entry->Number)) {
                    // evaluate if numbers array exists, and create it if needed
                    if (!isset($ro->PhoneNumbers->Entry)) { $ro->PhoneNumbers = new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryType(); }
                    // add number to numbers array
                    $ro->PhoneNumbers->Entry[] = new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType(
                        ($tc['Count'] > 1) ? $type . $tc['Count'] : $type, // add count to type if available count is greater then one
                        $entry->Number
                    );
                    // decrease available type count by one
                    $types[$type]['Count'] += 1;
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
        //$ro->FileAsMapping = 'LastFirstCompany';
        // execute command
        $rs = $this->RemoteCommonService->createItem($this->DataStore, $cid, $ro);

        // process response
        if ($rs->Contact[0]) {
			$co = clone $so;
			$co->ID = $rs->Contact[0]->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->Contact[0]->ItemId->ChangeKey;
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
     * @param string $iid - Collection Item State
     * @param ContactObject $so - Source Data
	 * 
	 * @return ContactObject
	 */
	public function updateCollectionItem(string $cid, string $iid, string $istate, ContactObject $so): ?ContactObject {

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
                $rm[] = $this->updateFieldExtendedByTag('14917', 'String', $so->Name->Prefix);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('14917', 'String');
            }
            // Suffix
            if (!empty($so->Name->Suffix)) {
                $rm[] = $this->updateFieldUnindexed('contacts:Generation', 'Generation', $so->Name->Suffix);
            }
            else {
                $rd[] = $this->deleteFieldUnindexed('contacts:Generation');
            }
            /*
            // Phonetic Last
            if (!empty($so->Name->PhoneticLast)) {
                $rm[] = $this->updateFieldExtendedByTag('32813', 'String', $so->Name->PhoneticLast);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('32813', 'String');
            }
            // Phonetic First
            if (!empty($so->Name->PhoneticFirst)) {
                $rm[] = $this->updateFieldExtendedByTag('32812', 'String', $so->Name->PhoneticFirst);
            }
            else {
                $rd[] = $this->deleteFieldExtendedByTag('32812', 'String');
            }
            */
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
            $rm[] = $this->updateFieldUnindexed('contacts:Birthday', 'Birthday', $so->BirthDay->format('Y-m-d\TH:i:s'));
        }
        else {
            $rd[] = $this->deleteFieldUnindexed('contacts:Birthday');
        }
        // Gender
        if (!empty($so->Gender)) {
            $rm[] = $this->updateFieldExtendedByTag('14925', 'String', $so->Gender);
        }
        else {
            $rd[] = $this->deleteFieldExtendedByTag('14925', 'String');
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
            $rm[] = $this->updateFieldUnindexed('contacts:WeddingAnniversary', 'WeddingAnniversary', $so->AnniversaryDay->format('Y-m-d\TH:i:s'));
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
        $types = [
            'BusinessPhone' => ['Max' => 2, 'Count' => 1],
            'BusinessFax' => ['Max' => 1, 'Count' => 1],
            'HomePhone' => ['Max' => 2, 'Count' => 1],
            'HomeFax' => ['Max' => 1, 'Count' => 1],
            'OtherTelephone' => ['Max' => 1, 'Count' => 1],
            'OtherFax' => ['Max' => 1, 'Count' => 1],
            'MobilePhone' => ['Max' => 1, 'Count' => 1],
            'CarPhone' => ['Max' => 1, 'Count' => 1],
            'Pager' => ['Max' => 1, 'Count' => 1],
            'Isdn' => ['Max' => 1, 'Count' => 1],
            'AssistantPhone' => ['Max' => 1, 'Count' => 1],
            'Callback' => ['Max' => 1, 'Count' => 1],
            'CompanyMainPhone' => ['Max' => 1, 'Count' => 1],
            'PrimaryPhone' => ['Max' => 1, 'Count' => 1],
            'RadioPhone' => ['Max' => 1, 'Count' => 1],
            'Telex' => ['Max' => 1, 'Count' => 1],
            'TtyTddPhone' => ['Max' => 1, 'Count' => 1]
        ];
        // update phone
        if (count($so->Phone) > 0) {
            foreach ($so->Phone as $entry) {
                // convert primary and secondary type to single type
                $type = $this->toPhoneType($entry->Type, $entry->SubType);
                // evaluate if type was converted
                $tc = (isset($types[$type])) ? $types[$type] : null;
                // process if index not used already
                if (isset($tc) && ($tc['Count'] <= $tc['Max']) && !empty($entry->Number)) {
                    $rm[] = $this->updateFieldIndexed(
                        'contacts:PhoneNumber',
                        ($tc['Count'] > 1) ? $type . $tc['Count'] : $type,
                        'PhoneNumbers',
                        new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryType(),
                        new \OCA\EWS\Components\EWS\Type\PhoneNumberDictionaryEntryType(
                            ($tc['Count'] > 1) ? $type . $tc['Count'] : $type, 
                            $entry->Number
                        )
                    );
                    // decrease available type count by one
                    $types[$type]['Count'] += 1;
                }
            }
        }
        // delete phone
        foreach ($types as $type => $tc) {
            while ($tc['Count'] <= $tc['Max']) {
                $rd[] = $this->deleteFieldIndexed(
                    'contacts:PhoneNumber',
                    ($tc['Count'] > 1) ? $type . $tc['Count'] : $type
                );
                $tc['Count'] += 1;
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
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, null, $rm, $rd);
        // process response
        if ($rs->Contact[0]) {
			$co = clone $so;
			$co->ID = $rs->Contact[0]->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->Contact[0]->ItemId->ChangeKey;
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
     * @param string $iid - Collection Item State
     * @param string $cid - Collection Item UUID
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItemUUID(string $cid, string $iid, string $istate, string $uuid): ?object {
		// request modifications array
        $rm = array();
        // construct update command object
        $rm[] = $this->updateFieldExtendedByName('PublicStrings', 'DAV:uid', 'String', $uuid);
        // execute request
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, null, null, $rm, null);
        // return response
        if ($rs->Contact[0]) {
            return (object) array('ID' => $rs->Contact[0]->ItemId->Id, 'UID' => $uuid, 'State' => $rs->Contact[0]->ItemId->ChangeKey);
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
	public function constructDefaultCollectionProperties(): object {

		// evaluate if default collection properties collection exisits
		if (!isset($this->DefaultCollectionProperties)) {
			// unindexed property names collection
			$_properties = [
				'folder:FolderId',
				'folder:FolderClass',
				'folder:ParentFolderId',
				'folder:DisplayName',
				'folder:TotalCount',
			];
			// construct property collection
			$this->DefaultCollectionProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			foreach ($_properties as $entry) {
				$this->DefaultCollectionProperties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($entry);
			}
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
    public function constructDefaultItemProperties(): object {
    
        // evaluate if default item properties collection exisits
		if (!isset($this->DefaultItemProperties)) {
			// unindexed property names collection
			$_properties = [
                'item:ItemId',
                'item:ParentFolderId',
                'item:DateTimeCreated',
                'item:DateTimeSent',
                'item:LastModifiedTime',
                'item:Categories',
                'item:Body',
                'item:Attachments',
                'contacts:DisplayName',
                'contacts:CompleteName',
                'contacts:PhoneticLastName',
                'contacts:PhoneticFirstName',
                'contacts:Birthday',
                'contacts:SpouseName',
                'contacts:WeddingAnniversary',
                'contacts:PhysicalAddresses',
                'contacts:PhoneNumbers',
                'contacts:EmailAddresses',
                'contacts:ImAddresses',
                'contacts:CompanyName',
                'contacts:Manager',
                'contacts:AssistantName',
                'contacts:Department',
                'contacts:JobTitle',
                'contacts:Profession',
                'contacts:OfficeLocation',
                'contacts:HasPicture',
			];
			// construct property collection
			$this->DefaultItemProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			foreach ($_properties as $entry) {
				$this->DefaultItemProperties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($entry);
			}
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
    public function createFieldExtendedById(string $collection, string $id, string $type, mixed $value): object {
        // create extended field object
        $o = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                $id,
                null,
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
    public function updateFieldExtendedById(string $collection, string $id, string $type, mixed $value): object {
        // create field update object
        $o = new \OCA\EWS\Components\EWS\Type\SetItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            $id,
            null,
            null,
            $type
        );
        // create field contact object
        $o->Contact = new \OCA\EWS\Components\EWS\Type\ContactItemType();
        $o->Contact->ExtendedProperty = new \OCA\EWS\Components\EWS\Type\ExtendedPropertyType(
            new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
                $collection,
                null,
                $id,
                null,
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
    public function deleteFieldExtendedById(string $collection, string $id, string $type): object {
        // create field delete object
        $o = new \OCA\EWS\Components\EWS\Type\DeleteItemFieldType();
        $o->ExtendedFieldURI = new \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType(
            $collection,
            null,
            $id,
            null,
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
	 * @param ContactItemType $so - item as vcard object
	 * 
	 * @return ContactObject item as contact object
	 */
	public function toContactObject(ContactItemType $so): ContactObject {

		// create object
		$co = new ContactObject();
        // Origin
		$co->Origin = 'R';
        // ID + State
        if (isset($so->ItemId)) {
            $co->ID = $so->ItemId->Id;
            $co->State = $so->ItemId->ChangeKey;
        }
        // Collection ID
        if (isset($so->ParentFolderId)) {
            $co->CID = $so->ParentFolderId->Id;
        }
        // Creation Date
        if (!empty($so->DateTimeCreated)) {
            $co->CreatedOn = new DateTime($so->DateTimeCreated);
        }
        // Modification Date
        if (!empty($so->DateTimeSent)) {
            $co->ModifiedOn = new DateTime($so->DateTimeSent);
        }
        if (!empty($so->LastModifiedTime)) {
            $co->ModifiedOn = new DateTime($so->LastModifiedTime);
        }
        // Label
        if (!empty($so->DisplayName)) {
            $co->Label = $so->DisplayName;
        }
		// Name
        if (isset($so->CompleteName)) {
            $co->Name->Last = $so->CompleteName->LastName;
            $co->Name->First = $so->CompleteName->FirstName;
            $co->Name->Other = $so->CompleteName->MiddleName;
            $co->Name->Prefix = $so->CompleteName->Title;
            $co->Name->Suffix = $so->CompleteName->Suffix;
            $co->Name->PhoneticLast = $so->CompleteName->YomiLastName;
            $co->Name->PhoneticFirst = $so->CompleteName->YomiLastName;
            $co->Name->Aliases = $so->CompleteName->Nickname;
        }
        // Phonetic Last Name
        if (!empty($so->PhoneticLastName)) {
            $co->Name->PhoneticLast =  new DateTime($so->PhoneticLastName);
        }
        // Phonetic First Name
        if (!empty($so->PhoneticFirstName)) {
            $co->Name->PhoneticFirst =  new DateTime($so->PhoneticFirstName);
        }
        // Birth Day
        if (!empty($so->Birthday)) {
            $co->BirthDay =  new DateTime($so->Birthday);
        }
        // Partner
        if (!empty($so->SpouseName)) {
            $co->Partner = $so->SpouseName;
        }
        // Anniversary Day
        if (!empty($so->WeddingAnniversary)) {
            $co->AnniversaryDay =  new DateTime($so->WeddingAnniversary);
        }
        // Address(es)
        if (isset($so->PhysicalAddresses)) {
            foreach($so->PhysicalAddresses->Entry as $entry) {
                $co->addAddress(
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
        if (isset($so->PhoneNumbers)) {
            foreach($so->PhoneNumbers->Entry as $entry) {
                [$primary, $secondary] = $this->fromPhoneType($entry->Key); 
                if (isset($primary)) {
                    $co->addPhone(
                        $primary,
                        $secondary,
                        $entry->_
                    );
                }
            }
        }
        // Email(s)
        if (isset($so->EmailAddresses)) {
            foreach($so->EmailAddresses->Entry as $entry) {
                $type = $this->fromEmailType($entry->Key);
                if (isset($type)) {
                    $co->addEmail(
                        $type, 
                        $entry->_
                    );
                }
            }
        }
        // IMPP(s)
        if (isset($so->ImAddresses)) {
            foreach($so->ImAddresses->Entry as $entry) {
                $co->addIMPP(
                    $entry->Key, 
                    $entry->_
                );
            }
        }
        // Manager Name
        if (!empty($so->Manager)) {
            $co->Name->Manager =  $so->Manager;
        }
        // Assistant Name
        if (!empty($so->AssistantName)) {
            $co->Name->Assistant =  $so->AssistantName;
        }
        // Occupation Organization
        if (!empty($so->CompanyName)) {
            $co->Occupation->Organization = $so->CompanyName;
        }
        // Occupation Department
        if (!empty($so->Department)) {
            $co->Occupation->Department = $so->Department;
        }
        // Occupation Title
        if (!empty($so->JobTitle)) {
            $co->Occupation->Title = $so->JobTitle;
        }
        // Occupation Role
        if (!empty($so->Profession)) {
            $co->Occupation->Role = $so->Profession;
        }
        // Occupation Location
        if (!empty($so->OfficeLocation)) {
            $co->Occupation->Location = $so->OfficeLocation;
        }
        // Relation
        //if ($so->RELATED) {
        //    $this->Relation = new ContactRelationObject($so->RELATED->parameters()['TYPE']->getValue(), $so->RELATED->getValue());
        //}
        // Tag(s)
        if (isset($so->Categories)) {
            foreach($so->Categories->String as $entry) {
                $co->addTag($entry);
            }
        }
        // Notes
        if (!empty($so->Body)) {
            $co->Notes = $so->Body->_;
        }
        // Sound
        //if ($so->SOUND) {
        //    $this->Sound = $so->SOUND->getValue();
        //}
        // URL / Website
        if (isset($co->URL)) {
            $this->URI = $so->URL->getValue();
        }

        // Attachment(s)
        if (isset($so->Attachments)) {
            foreach($so->Attachments->FileAttachment as $entry) {
                // evaluate mime type
                if ($entry->ContentType == 'application/octet-stream') {
                    $type = \OCA\EWS\Utile\MIME::fromFileName($entry->Name);
                } else {
                    $type = $entry->ContentType;
                }
                // evaluate attachemnt type
                if ($entry->IsContactPhoto || str_contains($entry->Name, 'ContactPicture')) {
                    $flag = 'CP';
                    $co->Photo->Type = 'data';
                    $co->Photo->Data = $entry->AttachmentId->Id;
                }
                else {
                    $flag = null;
                }
                $co->addAttachment(
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
        if (isset($so->ExtendedProperty)) {
            foreach ($so->ExtendedProperty as $entry) {
                switch ($entry->ExtendedFieldURI->PropertyName) {
                    case 'DAV:uid': // UUID
                        $co->UID = $entry->Value;
                        break;
                }
                switch ($entry->ExtendedFieldURI->PropertyTag) {
                    case '0x3007': // Created Date/Time
                        $co->CreatedOn = new DateTime($entry->Value);
                        break;
                    case '0x3008': // Modified Date/Time
                        $co->ModifiedOn = new DateTime($entry->Value);
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

		return $co;

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
    public function fromEmailType(string $type): ?string {

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
    public function toEmailType(string $type): string {

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
    public function fromPhoneType(string $type): ?array {
        
        $_tm = [
            'BusinessPhone' => ['WORK','VOICE'],
            'BusinessPhone2' => ['WORK','VOICE'],
            'BusinessFax' => ['WORK','FAX'],
            'HomePhone' => ['HOME','VOICE'],
            'HomePhone2' => ['HOME','VOICE'],
            'HomeFax' => ['HOME','FAX'],
            'OtherTelephone' => ['OTHER','VOICE'],
            'OtherFax' => ['OTHER','FAX'],
            'MobilePhone' => ['CELL', null],
            'CarPhone' => ['CAR', null],
            'Pager' => ['PAGER', null],
            'Isdn' => ['ISDN', null],
            'AssistantPhone' => [null, null],
            'Callback' => [null, null],
            'CompanyMainPhone' => [null, null],
            'PrimaryPhone' => [null, null],
            'RadioPhone' => [null, null],
            'Telex' => [null, null],
            'TtyTddPhone' => [null, null]
        ];

        // evaluate if type value exists
		if (isset($_tm[$type])) {
			// return converted type value
			return $_tm[$type];
		} else {
            // return default type value
			return [null, null];
		}

    }

    /**
     * convert local telephone type to remote type
     * 
     * @since Release 1.0.0
     * 
	 * @param sting $primary - contact object telephone type
	 * 
	 * @return string|null remote telephone type
	 */
    public function toPhoneType(string $primary, $secondary): ?string {
        
        if ($primary == 'WORK' && $secondary == 'VOICE') {
            return 'BusinessPhone';
        }
        elseif ($primary == 'WORK' && $secondary == 'FAX') {
            return 'BusinessFax';
        }
        elseif ($primary == 'HOME' && $secondary == 'VOICE') {
            return 'HomePhone';
        }
        elseif ($primary == 'HOME' && $secondary == 'FAX') {
            return 'HomeFax';
        }
        elseif ($primary == 'OTHER' && $secondary == 'VOICE') {
            return 'OtherTelephone';
        }
        elseif ($primary == 'OTHER' && $secondary == 'FAX') {
            return 'OtherFax';
        }
        elseif ($primary == 'CELL') {
            return 'MobilePhone';
        }
        elseif ($primary == 'CAR') {
            return 'CarPhone';
        }
        elseif ($primary == 'PAGER') {
            return 'Pager';
        }
        elseif ($primary == 'ISDN') {
            return 'Isdn';
        }

        // Following types are currently not convertable
        //'AssistantPhone' 'Callback' 'CompanyMainPhone' 'PrimaryPhone' 'RadioPhone' 'Telex' 'TtyTddPhone'

        return null;
        
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
    public function fromAddressType(string $type): ?string {

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
    public function toAddressType(string $type): string {

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
