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
use DateTimeZone;

class EventObject {

    private bool $Journaling = false;

    private $Data = null;

    private ?string $Origin = null;                 // Source System / L - Local / R - Remote
    private ?string $ID = null;                     // Source System Id
    private ?string $UUID = null;                   // Object UUID
    private ?string $CID = null;                    // Source System Object Collection Affiliation Id
    private ?string $State = null;                  // Source System Object State
    private ?DateTime $CreatedOn = null;            // Source System Creation Date/Time
    private ?DateTime $ModifiedOn = null;           // Source System Modification Date/Time
    private ?DateTime $StartsOn = null;             // Event Start Date/Time
    private ?DateTimeZone $StartsTZ = null;         // Event Start Time Zone
    private ?DateTime $EndsOn = null;               // Event End Date/Time
    private ?DateTimeZone $EndsTZ = null;           // Event End Time Zone
    private ?DateTimeZone $TimeZone = null;         // Event Time Zone
    private ?string $Label = null;                  // Event Title/Summary
    private ?string $Notes = null;                  // Event Notes
    private ?string $Location = null;               // Event Location
    private ?string $Availability = null;           // Event Free Busy Status / F - Free / B - Busy
    private ?string $Priority = null;               // Event Priority / 0 - Low / 1 - Normal / 2 - High
    private ?string $Sensitivity = null;            // Event Sensitivity / 0 - Normal / 1 - Personnal / 2 - Private / 3 - Confidential
    private ?string $Color = null;                  // Event Display Color
    private array $Tags = [];                       // Event Categories
    private ?EventOrganizerObject $Organizer;       // Event Organizer Name/Email
    private array $Attendee = [];                   // Event Attendees Name/Email/Attendance
    private array $Notifications = [];              // Event Reminders/Alerts
    private ?EventOccurrenceObject $Occurrence = null; // Event Recurrance Data
    private array $Attachments = [];                // Event Attachments
    private ?array $Other = [];
	
	public function __construct($data = null) {
        $this->Data = (object) array();
        $this->Data->Original = (object) array();
        $this->Data->Changed = (object) array();
        $this->Organizer = new EventOrganizerObject();
        $this->Occurrence = new EventOccurrenceObject();
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
        $this->Journaling = true;
    }

    public function addAttachment(string $store, string $id = null, ?string $name = null, ?string $type = null, ?string $encoding = null, ?string $size = null, ?string $data = null) {
        $this->Attachments[] = new EventAttachmentObject($store, $id, $name, $type, $encoding, $size, $data);
    }

    public function addTag(string $tag) {
        $this->Tags[] = $tag;
    }

    public function addAttendee(string $address, ?string $name, ?string $type, string $attendance) {
        $this->Attendee[] = new EventAttendeeObject($address, $name, $type, $attendance);
    }

    public function addNotification(string $type, string $pattern, mixed $when) {
        $this->Notifications[] = new EventNotificationObject($type, $pattern, $when);
    }
}
