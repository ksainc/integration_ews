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

class RemoteContactsService2007 extends RemoteContactsService {
	
	public function __construct (string $appName,
								LoggerInterface $logger,
								RemoteCommonService $RemoteCommonService) {
        parent::__construct($appName, $logger, $RemoteCommonService);
        $this->logger = $logger;
		$this->RemoteCommonService = $RemoteCommonService;
	}

    public function configure($configuration, EWSClient $DataStore) : void {
		
        parent::configure($configuration, $DataStore);
		// assign configuration
		$this->Configuration = $configuration;
		// assign remote data store
		$this->DataStore = $DataStore;
		
	}
    
    /**
     * construct collection of default remote object properties 
     * 
     * @since Release 1.0.15
	 * 
	 * @return object
	 */
    public function constructDefaultItemProperties(): object {

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
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('item:Sensitivity');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:AssistantName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Birthday');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:BusinessHomePage');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Companies');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:CompanyName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:CompleteName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Department');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:DisplayName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:FileAs');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:FileAsMapping');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:GivenName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Children');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Initials');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:JobTitle');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Manager');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:MiddleName');
			$p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Nickname');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:OfficeLocation');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Profession');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:SpouseName');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:Surname');
            $p->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType('contacts:WeddingAnniversary');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:EmailAddress', 'EmailAddress1');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:EmailAddress', 'EmailAddress2');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:EmailAddress', 'EmailAddress3');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:ImAddress', 'ImAddress1');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:ImAddress', 'ImAddress2');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:ImAddress', 'ImAddress3');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:Street', 'Home');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:City', 'Home');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:State', 'Home');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:CountryOrRegion', 'Home');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:PostalCode', 'Home');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:Street', 'Business');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:City', 'Business');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:State', 'Business');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:CountryOrRegion', 'Business');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:PostalCode', 'Business');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:Street', 'Other');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:City', 'Other');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:State', 'Other');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:CountryOrRegion', 'Other');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhysicalAddress:PostalCode', 'Other');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'AssistantPhone');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'BusinessFax');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'BusinessPhone');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'BusinessPhone2');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'Callback');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'CarPhone');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'CompanyMainPhone');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'HomeFax');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'HomePhone');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'HomePhone2');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'Isdn');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'MobilePhone');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'OtherFax');
            $p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'OtherTelephone');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'Pager');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'PrimaryPhone');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'RadioPhone');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'Telex');
            //$p->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType('contacts:PhoneNumber', 'TtyTddPhone');

			$this->DefaultItemProperties = $p;
		}

		return $this->DefaultItemProperties;

	}

	/**
     * create collection item in remote storage
     * 
     * @since Release 1.0.15
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
            $ro->ExtendedProperty[] = $this->createFieldExtendedByTag('14925', 'String', $so->Gender);
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
        //$ro->FileAsMapping = 'LastFirstCompany';
        // execute command
        $rs = $this->RemoteCommonService->createItem($this->DataStore, $cid, $ro);

        // process response
        if ($rs->CalendarItem[0]) {
			$co = clone $so;
			$co->ID = $rs->CalendarItem[0]->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->CalendarItem[0]->ItemId->ChangeKey;
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
     * @since Release 1.0.15
     * 
     * @param string $cid - Collection ID
     * @param string $iid - Collection Item ID
     * @param string $istate - Collection Item State
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
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, $istate, null, $rm, $rd);
        // process response
        if ($rs->CalendarItem[0]) {
			$co = clone $so;
			$co->ID = $rs->CalendarItem[0]->ItemId->Id;
            $co->CID = $cid;
			$co->State = $rs->CalendarItem[0]->ItemId->ChangeKey;
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

}
