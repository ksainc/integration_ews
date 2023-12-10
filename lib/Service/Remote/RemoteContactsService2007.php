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
                'item:Sensitivity',
                'contacts:AssistantName',
                'contacts:Birthday',
                'contacts:BusinessHomePage',
                'contacts:Companies',
                'contacts:CompanyName',
                'contacts:CompleteName',
                'contacts:Department',
                'contacts:DisplayName',
                'contacts:FileAs',
                'contacts:FileAsMapping',
                'contacts:GivenName',
                'contacts:Children',
                'contacts:Initials',
                'contacts:JobTitle',
                'contacts:Manager',
                'contacts:MiddleName',
                'contacts:Nickname',
                'contacts:OfficeLocation',
                'contacts:Profession',
                'contacts:SpouseName',
                'contacts:Surname',
                'contacts:WeddingAnniversary',
			];
			// construct property collection
			$this->DefaultItemProperties = new \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType();
			foreach ($_properties as $entry) {
				$this->DefaultItemProperties->FieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType($entry);
			}
            
            // indexed property names collection
			$_properties = [
                'contacts:EmailAddress', 'EmailAddress1',
                'contacts:EmailAddress', 'EmailAddress2',
                'contacts:EmailAddress', 'EmailAddress3',
                'contacts:ImAddress', 'ImAddress1',
                'contacts:ImAddress', 'ImAddress2',
                'contacts:ImAddress', 'ImAddress3',
                'contacts:PhysicalAddress:Street', 'Home',
                'contacts:PhysicalAddress:City', 'Home',
                'contacts:PhysicalAddress:State', 'Home',
                'contacts:PhysicalAddress:CountryOrRegion', 'Home',
                'contacts:PhysicalAddress:PostalCode', 'Home',
                'contacts:PhysicalAddress:Street', 'Business',
                'contacts:PhysicalAddress:City', 'Business',
                'contacts:PhysicalAddress:State', 'Business',
                'contacts:PhysicalAddress:CountryOrRegion', 'Business',
                'contacts:PhysicalAddress:PostalCode', 'Business',
                'contacts:PhysicalAddress:Street', 'Other',
                'contacts:PhysicalAddress:City', 'Other',
                'contacts:PhysicalAddress:State', 'Other',
                'contacts:PhysicalAddress:CountryOrRegion', 'Other',
                'contacts:PhysicalAddress:PostalCode', 'Other',
                'contacts:PhoneNumber', 'AssistantPhone',
                'contacts:PhoneNumber', 'BusinessFax',
                'contacts:PhoneNumber', 'BusinessPhone',
                'contacts:PhoneNumber', 'BusinessPhone2',
                //'contacts:PhoneNumber', 'Callback',
                'contacts:PhoneNumber', 'CarPhone',
                //'contacts:PhoneNumber', 'CompanyMainPhone',
                'contacts:PhoneNumber', 'HomeFax',
                'contacts:PhoneNumber', 'HomePhone',
                'contacts:PhoneNumber', 'HomePhone2',
                //'contacts:PhoneNumber', 'Isdn',
                'contacts:PhoneNumber', 'MobilePhone',
                'contacts:PhoneNumber', 'OtherFax',
                'contacts:PhoneNumber', 'OtherTelephone',
                //'contacts:PhoneNumber', 'Pager',
                //'contacts:PhoneNumber', 'PrimaryPhone',
                //'contacts:PhoneNumber', 'RadioPhone',
                //'contacts:PhoneNumber', 'Telex',
                //'contacts:PhoneNumber', 'TtyTddPhone',
			];
			foreach ($_properties as $entry) {
				$this->DefaultItemProperties->IndexedFieldURI[] = new \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType($entry[0], $entry[1]);
			}
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
        $rs = $this->RemoteCommonService->updateItem($this->DataStore, $cid, $iid, $istate, null, $rm, $rd);
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

}
