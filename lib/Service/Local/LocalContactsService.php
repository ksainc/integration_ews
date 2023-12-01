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

namespace OCA\EWS\Service\Local;

use Datetime;
use DateTimeZone;
use Psr\Log\LoggerInterface;
use OCA\DAV\CardDAV\CardDavBackend;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Db\ContactsUtile;
use \OCA\EWS\Objects\ContactCollectionObject;
use \OCA\EWS\Objects\ContactObject;

use Sabre\VObject\Reader;
use Sabre\VObject\Component\VCard;

class LocalContactsService {
	/**
	 * @var LoggerInterface
	 */
	private $logger;
    /**
	 * @var Object
	 */
	private $Configuration;
    /**
	 * @var CardDavBackend
	 */
	public ?CardDavBackend $DataStore = null;
    /**
	 * @var ContactsUtile
	 */
    private $ContactsUtile;

	public function __construct (string $appName, LoggerInterface $logger, ContactsUtile $ContactsUtile) {
		$this->logger = $logger;
        $this->ContactsUtile = $ContactsUtile;
	}

    public function configure($configuration, CardDavBackend $DataStore) : void {
		
		// assign configuration
		$this->Configuration = $configuration;
		// assign local data store
		$this->DataStore = $DataStore;
		
	}
    
	/**
     * retrieve list of all collections in local storage
     * 
	 * @param string $uid - User ID
	 * 
	 * @return array of collections
	 */
	public function listCollections(string $uid): array {

        // retrieve all local collections 
        $collections = $this->DataStore->getAddressBooksForUser('principals/users/' . $uid);
		// construct collections list
		$data = array();
		foreach ($collections as $entry) {
			$data[] = array('id' => $entry['id'], 'name' => $entry['{DAV:}displayname'], 'uri' => $entry['uri']);
		}
        // return collections list
		return $data;

    }

	 /**
     * retrieve properties for specific collection from local storage
     * 
	 * @param string $cid - Collection Id
	 * 
	 * @return object of collection properties
	 */
	public function fetchCollection(string $cid): ?ContactCollectionObject {

        // retrieve collection properties
        $cc = $this->DataStore->getAddressBookById($cid);

        if (isset($cc)) {
            return new ContactCollectionObject(
                $cc['id'],
                $cc['{DAV:}displayname'],
                $cc['{http://sabredav.org/ns}sync-token']
            );
        }
        else {
            return null;
        }
    }
    
    /**
     * create collection in local storage
     * 
     * @param string $uid - User ID
	 * @param string $cid - Collection URI
     * @param string $name - Collection Name
	 * 
	 * @return ContactCollectionObject
	 */
	public function createCollection(string $uid, string $cid, string $name): ?ContactCollectionObject {

        // check for user id and collection - must contain to create
        if (!empty($uid) && !empty($cid)) {
            // create item in data store
            $result = $this->DataStore->createAddressBook(
                'principals/users/' . $uid, 
                $cid, 
                array('{DAV:}displayname' => $name)
            );
        }
        // return collection object or null
        if (isset($result)) {
            return $this->fetchCollection($result);
        } else {
            return null;
        }

    }

    /**
     * delete collection from local storage
     * 
	 * @param string $cid - Collection ID
	 * 
	 * @return bool true - successfully delete / false - failed to delete
	 */
	public function deleteCollection(string $cid): bool {

        // check for id - must contain id to delete
        if (!empty($cid)) {
            // delete item in data store
            $result = $this->DataStore->deleteAddressBook($cid);
        }
        // return operation result
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

	/**
     * retrieve changes for specific collection from local storage
     * 
	 * @param string $cid - Collection Id
     * @param string $state - Collection Id
	 * 
	 * @return array of collection changes
	 */
	public function fetchCollectionChanges(string $cid, string $state): array {

        // retrieve collection chamges
        $lcc = $this->DataStore->getChangesForAddressBook($cid, $state, null, null);
        // return collection chamges
		return $lcc;
        
    }

    /**
     * find collection object by uuid in local storage
     * 
	 * @param string $cid - Collection ID
     * @param string $uuid - Item UUID
	 * 
	 * @return ContactObject ContactObject - successfully retrieved / null - failed to retrieve
	 */
	public function findCollectionItemByUUID(string $cid, string $uuid): ?ContactObject {
        
        // search data store for object
        $lo = $this->ContactsUtile->findByUUID($cid, $uuid);
        // evaluate result
        if (is_array($lo) && count($lo) > 0) {
            $lo = $lo[0];
            // convert string data to vcard then to contact object
            $co = $this->toContactObject(Reader::read($lo['carddata']));
            $co->ID = $lo['uri'];
            $co->UID = $lo['uid'];
            $co->CID = $lo['addressbookid'];
            $co->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
            $co->State = trim($lo['etag'],'"');
            // return contact object
            return $co;
        } else {
            // return null
            return null;
        }

    }

	/**
     * retrieve collection item from local storage
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
	 * 
	 * @return ContactObject ContactObject - successfully retrieved / null - failed to retrieve
	 */
	public function fetchCollectionItem(string $cid, string $iid): ?ContactObject {

        // retrieve collection item
		//$lo = $this->DataStore->getCard($cid, $iid);
        $lo = $this->ContactsUtile->findByURI($cid, $iid);
		// evaluate result
        if (is_array($lo) && count($lo) > 0) {
            $lo = $lo[0];
            // convert to contact object
            $co = $this->toContactObject(Reader::read($lo['carddata']));
            $co->ID = $lo['uri'];
            $co->CID = $lo['addressbookid'];
            $co->ModifiedOn = new DateTime(date("Y-m-d H:i:s", $lo['lastmodified']));
            $co->State = trim($lo['etag'],'"');
            // return contact object
            return $co;
        } else {
            // return null
            return null;
        }

    }

    /**
     * create collection item in local storage
     * 
	 * @param string $cid - Collection ID
     * @param ContactObject $data - Item Data
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function createCollectionItem(string $cid, ContactObject $data): ?object {

        // convert contact object to vcard object
        $lo = $this->fromContactObject($data);
        // generate item id
        $loid = \OCA\EWS\Utile\UUID::v4() . '.vcf';
        // create item in data store
        $result = $this->DataStore->createCard($cid, $loid, $lo->serialize());
        // return status object or null
        if ($result) {
            return (object) array('ID' => $loid, 'UID' => $lo->UID->getValue(), 'State' => trim($result,'"'));
        } else {
            return null;
        }

    }
    
    /**
     * update collection item in local storage
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
     * @param ContactObject $co - Item Data
	 * 
	 * @return object Status Object - item id, item uuid, item state token / Null - failed to create
	 */
	public function updateCollectionItem(string $cid, string $iid, ContactObject $co): ?object {

        // check for id - must contain id to update
        if (!empty($iid)) {
            // convert contact object to vcard object
            $lo = $this->fromContactObject($co);
            // update item in data store
            $result = $this->DataStore->updateCard($cid, $iid, $lo->serialize());
        }
        // return status object or null
        if ($result) {
            return (object) array('ID' => $iid, 'UID' => $co->UID, 'State' => trim($result,'"'));
        } else {
            return null;
        }

    }
    
    /**
     * delete collection item from local storage
     * 
	 * @param string $cid - Collection ID
     * @param string $iid - Item ID
	 * 
	 * @return bool true - successfully delete / false - failed to delete
	 */
	public function deleteCollectionItem(string $cid, string $iid): bool {

        // check for id - must contain id to delete
        if (!empty($iid)) {
            // delete item in data store
            $result = $this->DataStore->deleteCard($cid, $iid);
        }
        // return operation result
        if ($result) {
            return true;
        } else {
            return false;
        }

    }
    
    /**
     * convert vcard object to contact object
     * 
	 * @param VCard $vo - source object
	 * 
	 * @return ContactObject converted object
	 */
	public function toContactObject(VCard $vo): ContactObject {

		// construct contact object
		$co = new ContactObject();
        // UUID
        if (isset($vo->UID)) {
            $co->UID = trim($vo->UID->getValue());
        }
        // Label
        if (isset($vo->FN)) {
            $co->Label = trim($vo->FN->getValue());
        }
		// Name
        if (isset($vo->N)) {
            $p = $vo->N->getParts();
            $co->Name->Last = trim($p[0]);
            $co->Name->First = trim($p[1]);
            $co->Name->Other = trim($p[2]);
            $co->Name->Prefix = trim($p[3]);
            $co->Name->Suffix = trim($p[4]);
            $co->Name->PhoneticLast = trim($p[6]);
            $co->Name->PhoneticFirst = trim($p[7]);
            $co->Name->Aliases = trim($p[5]);
            unset($p);
        }
        // Aliases
        if (isset($vo->NICKNAME)) {
            if (empty($co->Name->Aliases)) {
                $co->Name->Aliases .= trim($vo->NICKNAME->getValue());
            }
            else {
                $co->Name->Aliases .= ' ' . trim($vo->NICKNAME->getValue());
            }
        }
        // Photo
        if (isset($vo->PHOTO)) {
            $p = $vo->PHOTO->getValue();
            if (str_starts_with($p, 'data:')) {
                $p = explode(';', $p);
                if (count($p) == 2) {
                    $p[0] = explode(':', $p[0]);
                    $p[1] = explode(',', $p[1]);
                    $co->Photo->Type = 'data';
                    $co->Photo->Data = $vo->UID;
                    $co->addAttachment(
                        $vo->UID,
                        $vo->UID . '.' . \OCA\EWS\Utile\MIME::toExtension($p[0][1]),
                        $p[0][1],
                        'B64',
                        'CP',
                        null,
                        $p[1][1]
                    );
                }
            } elseif (str_starts_with($p, 'uri:')) {
                $co->Photo->Type = 'uri';
                $co->Photo->Data = trim(substr($p,4));
            }
            unset($p);
        }
        // Gender
        if (isset($vo->GENDER)) {
            $co->Gender = trim($vo->GENDER->getValue());
        }
        // Birth Day
        if (isset($vo->BDAY)) {
            $co->BirthDay =  new DateTime($vo->BDAY->getValue());
        }
        // Anniversary Day
        if (isset($vo->ANNIVERSARY)) {
            $co->AnniversaryDay =  new DateTime($vo->ANNIVERSARY->getValue());
        }
        // Address(es)
        if (isset($vo->ADR)) {
            foreach($vo->ADR as $entry) {
                $p = $entry->getParts();
                $co->addAddress(
                    strtoupper($entry->parameters()['TYPE']->getValue()),
                    trim($p[2]),
                    trim($p[3]),
                    trim($p[4]),
                    trim($p[5]),
                    trim($p[6])
                );
                unset($p);
            }
        }
        // Phone(s)
        if (isset($vo->TEL)) {
            foreach($vo->TEL as $entry) {
                $co->addPhone(
                    strtoupper(trim($entry->parameters()['TYPE']->getValue())),
                    null, 
                    trim($entry->getValue())
                );
            }
        }
        // Email(s)
        if (isset($vo->EMAIL)) {
            foreach($vo->EMAIL as $entry) {
                $co->addEmail(
                    strtoupper(trim($entry->parameters()['TYPE']->getValue())), 
                    trim($entry->getValue())
                );
            }
        }
        // IMPP(s)
        if (isset($vo->IMPP)) {
            foreach($vo->IMPP as $entry) {
                $co->addIMPP(
                    strtoupper(trim($entry->parameters()['TYPE']->getValue())), 
                    trim($entry->getValue())
                );
            }
        }
        // Time Zone
        if (isset($vo->TZ)) {
            $co->TimeZone = trim($vo->TZ->getValue());
        }
        // Geolocation
        if (isset($vo->GEO)) {
            $co->Geolocation = trim($vo->GEO->getValue());
        }
        // Manager
		if (isset($vo->{'X-MANAGERSNAME'})) {
			$co->Manager = trim($vo->{'X-MANAGERSNAME'}->getValue());
		}
        // Assistant
		if (isset($vo->{'X-ASSISTANTNAME'})) {
			$co->Assistant = trim($vo->{'X-ASSISTANTNAME'}->getValue());
		}
        // Occupation Organization
        if (isset($vo->ORG)) {
			$co->Occupation->Organization = trim($vo->ORG->getValue());
		}
		// Occupation Title
        if (isset($vo->TITLE)) { 
			$co->Occupation->Title = trim($vo->TITLE->getValue()); 
		}
		// Occupation Role
		if (isset($vo->ROLE)) {
			$co->Occupation->Role = trim($vo->ROLE->getValue());
		}
		// Occupation Logo
		if (isset($vo->LOGO)) {
			$co->Occupation->Logo = trim($vo->LOGO->getValue());
		}
                
        // Relation
        if (isset($vo->RELATED)) {
            $co->addRelation(
				strtoupper(trim($vo->RELATED->parameters()['TYPE']->getValue())),
				trim($vo->RELATED->getValue())
			);
        }
        // Tag(s)
        if (isset($vo->CATEGORIES)) {
            foreach($vo->CATEGORIES->getParts() as $entry) {
                $co->addTag(
                    trim($entry)
                );
            }
        }
        // Notes
        if (isset($vo->NOTE)) {
            if (!empty(trim($vo->NOTE->getValue()))) {
                $co->Notes = trim($vo->NOTE->getValue());
            }
        }
        // Sound
        if (isset($vo->SOUND)) {
            $co->Sound = trim($vo->SOUND->getValue());
        }
        // URL / Website
        if (isset($vo->URL)) {
            $co->URI = trim($vo->URL->getValue());
        }

        // return contact object
		return $co;

    }

    /**
     * Convert contact object to vcard object
     * 
	 * @param ContactObject $co - source object
	 * 
	 * @return VCard converted object
	 */
    public function fromContactObject(ContactObject $co): VCard {

        // construct vcard object
        $vo = new VCard();
        // UID
        if (isset($co->UID)) {
            $vo->UID->setValue($co->UID);
        } else {
            $vo->UID->setValue(\OCA\EWS\Utile\UUID::v4());
        }
        // Label
        if (isset($co->Label)) {
            $vo->add('FN', $co->Label);
        }
        // Name
        if (isset($co->Name)) {
            $vo->add(
                'N',
                array(
                    $co->Name->Last,
                    $co->Name->First,
                    $co->Name->Other,
                    $co->Name->Prefix,
                    $co->Name->Suffix,
                    $co->Name->PhoneticLast,
                    $co->Name->PhoneticFirst,
                    $co->Name->Aliases
            ));
        }
        // Photo
        if (isset($co->Photo)) {
            if ($co->Photo->Type == 'uri') {
                $vo->add(
                    'PHOTO',
                    'uri:' . $co->Photo->Data
                );
            } elseif ($co->Photo->Type == 'data') {
                $k = array_search($co->Photo->Data, array_column($co->Attachments, 'Id'));
                if ($k !== false) {
                    switch ($co->Attachments[$k]->Encoding) {
                        case 'B':
                            $vo->add(
                                'PHOTO',
                                'data:' . $co->Attachments[$k]->Type . ';base64,' . base64_encode($co->Attachments[$k]->Data)
                            );
                            break;
                        case 'B64':
                            $vo->add(
                                'PHOTO',
                                'data:' . $co->Attachments[$k]->Type . ';base64,' . $co->Attachments[$k]->Data
                            );
                            break;
                    }
                }
            }
        }
        // Gender
        if (isset($co->Gender)) {
            $vo->add(
                'GENDER',
                $co->Gender
            );
        }
        // Birth Day
        if (isset($co->BirthDay)) {
            $vo->add(
                'BDAY',
                $co->BirthDay->format('Y-m-d\TH:i:s\Z')
            );
        }
        // Anniversary Day
        if (isset($co->AnniversaryDay)) {
            $vo->add(
                'ANNIVERSARY',
                $co->AnniversaryDay->format('Y-m-d\TH:i:s\Z')
            );
        }
        // Address(es)
        if (count($co->Address) > 0) {
            foreach ($co->Address as $entry) {
                $vo->add('ADR',
                    array(
                        '',
                        '',
                        $entry->Street,
                        $entry->Locality,
                        $entry->Region,
                        $entry->Code,
                        $entry->Country,
                    ),
                    array (
                        'TYPE'=>$entry->Type
                    )
                );
            }
        }
        // Phone(s)
        if (count($co->Phone) > 0) {
            foreach ($co->Phone as $entry) {
                $vo->add(
                    'TEL', 
                    $entry->Number,
                    array (
                        'TYPE'=>$entry->Type
                    )
                );
            }
        }
        // Email(s)
        if (count($co->Email) > 0) {
            foreach ($co->Email as $entry) {
                $vo->add(
                    'EMAIL', 
                    $entry->Address,
                    array (
                        'TYPE'=>$entry->Type
                    )
                );
            }
        }
        // IMPP(s)
        if (count($co->IMPP) > 0) {
            foreach ($co->IMPP as $entry) {
                $vo->add(
                    'IMPP', 
                    $entry->Address,
                    array (
                        'TYPE'=>$entry->Type
                    )
                );
            }
        }
        // Time Zone
        if (isset($co->TimeZone)) {
            $vo->add(
                'TZ',
                $co->TimeZone
            );
        }
        // Geolocation
        if (isset($co->Geolocation)) {
            $vo->add(
                'GEO',
                $co->Geolocation
            );
        }
        // Manager Name
		if (!empty($co->Manager)) {
            $vo->add(
                'X-MANAGERSNAME',
                $co->Manager
            );
		}
        // Assistant Name
		if (!empty($co->Assistant)) {
            $vo->add(
                'X-ASSISTANTNAME',
                $co->Assistant
            );
		}
        // Occupation Organization
        if (isset($co->Occupation->Organization)) {
            $vo->add(
                'ORG',
                $co->Occupation->Organization
            );
        }
        // Occupation Title
        if (isset($co->Occupation->Title)) {
            $vo->add(
                'TITLE',
                $co->Occupation->Title
            );
        }
        // Occupation Role
        if (isset($co->Occupation->Role)) {
            $vo->add(
                'ROLE',
                $co->Occupation->Role
            );
        }
        // Occupation Logo
        if (isset($co->Occupation->Logo)) {
            $vo->add(
                'LOGO',
                $co->Occupation->Logo
            );
        }
        // Relation(s)
        if (count($co->Relation) > 0) {
            foreach ($co->Relation as $entry) {
                $vo->add(
                    'RELATED', 
                    $entry->Value,
                    array (
                        'TYPE'=>$entry->Type
                    )
                );
            }
        }
        // Tag(s)
        if (count($co->Tags) > 0) {
            $vo->add('CATEGORIES', $co->Tags);
        }
        // Notes
        if (isset($co->Notes)) {
            $vo->add(
                'NOTE',
                $co->Notes
            );
        }
        // Sound
        if (isset($co->Sound)) {
            $vo->add(
                'SOUND',
                $co->Sound
            );
        }
        // URL / Website
        if (isset($co->URI)) {
            $vo->add(
                'URL',
                $co->URI
            );
        }

        // return vcard object
        return $vo;

    }
    
}
