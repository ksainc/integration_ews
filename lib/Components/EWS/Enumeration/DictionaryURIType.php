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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Identifies the dictionary that contains the member to return.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DictionaryURIType extends Enumeration
{
    /**
     * Represents the e-mail address of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_EMAIL_ADDRESS = 'contacts:EmailAddress';

    /**
     * Represents the instant messaging address of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_IM_ADDRESS = 'contacts:ImAddress';

    /**
     * Represents the phone number of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHONE_NUMBER = 'contacts:PhoneNumber';

    /**
     * Represents the city of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHYSICAL_ADDRESS_CITY = 'contacts:PhysicalAddress:City';

    /**
     * Represents the country of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHYSICAL_ADDRESS_COUNTRY = 'contacts:PhysicalAddress:Country';

    /**
     * Represents the postal code of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHYSICAL_ADDRESS_POSTAL_CODE = 'contacts:PhysicalAddress:PostalCode';

    /**
     * Represents the state of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHYSICAL_ADDRESS_STATE = 'contacts:PhysicalAddress:State';

    /**
     * Represents the street address of a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CONTACTS_PHYSICAL_ADDRESS_STREET = 'contacts:PhysicalAddress:Street';

    /**
     * Represents a member of a distribution list.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DISTRIBUTION_LIST_MEMBERS_MEMBER = 'distributionlist:Members:Member';

    /**
     * Represents the message header of an item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ITEM_INTERNET_MESSAGE_HEADER = 'item:InternetMessageHeader';
}
