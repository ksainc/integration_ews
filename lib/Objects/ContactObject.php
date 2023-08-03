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

namespace OCA\EWS\Objects;

use DateTime;

class ContactObject {

    private bool $Journalling = false;

    private $Data = null;

    private ?string $ID = null;
	private ?string $UID = null;
    private ?string $CID = null;
    private ?string $State = null;
    private ?DateTime $CreatedOn = null;
    private ?DateTime $ModifiedOn = null;
    private ?string $Label = null;
	private ?ContactNameObject $Name = null;
    private ?string $Aliases = null;
    private ?ContactPhotoObject $Photo = null;
    private ?DateTime $BirthDay = null;
    private ?string $Gender = null;
    private ?string $Partner = null;
	private ?DateTime $AnniversaryDay = null;
	private array $Address = [];
	private array $Phone = [];
    private array $Email = [];
    private array $IMPP = [];
    private ?string $TimeZone = null;
    private ?string $Geolocation = null;
    private ?ContactOccupationObject $Occupation = null;
    private ?array $Relation = [];
    private array $Tags = [];
    private ?string $Notes = null;
    private ?string $Sound = null;
    private ?string $URI = null;
    private array $Attachments = [];
    private ?array $Other = [];
	
	public function __construct($data = null) {
        $this->Data = (object) array();
        $this->Data->Original = (object) array();
        $this->Data->Changed = (object) array();
        $this->Name = new ContactNameObject();
        $this->Photo = new ContactPhotoObject();
        $this->Occupation = new ContactOccupationObject();
	}

    public function __set($name, $value) {
        $this->$name = $value;
        if ($this->Journalling) {
            $this->Data->Changed->$name = $this->$name;
        } else {
            $this->Data->Original->$name = $this->$name;
        }
    }

    public function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            return null;
        }
    }

    public function __isset($name) {
        return isset($this->$name);
    }

    public function activateJournal() {
        $this->Journalling = true;
    }

    public function addEmail(string $type, string $address) {
        $this->Email[] = new ContactEmailObject($type, $address);
    }

    public function addPhone(string $type, ?string $subtype, string $number) {
        $this->Phone[] = new ContactPhoneObject($type, $subtype, $number);
    }

    public function addAddress($type, ?string $street = null, ?string $locality = null, ?string $region = null, ?string $code = null, ?string $country = null) {
        $this->Address[] = new ContactAddressObject($type, $street, $locality, $region, $code, $country);
    }

    public function addIMPP(string $type, string $address) {
        $this->IMPP[] = new ContactIMPPObject($type, $address);
    }

    public function addTag(string $tag) {
        $this->Tags[] = $tag;
    }

    public function addRelation(string $type, string $value) {
        $this->Phone[] = new ContactRelationObject($type, $value);
    }
    
    public function addAttachment(string $id, ?string $name = null, ?string $type = null, ?string $encoding = null, ?string $data = null) {
        $this->Attachments[$id] = new ContactAttachmentObject($id, $name, $type, $encoding, $data);
    }
}
