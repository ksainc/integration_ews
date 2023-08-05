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
 * Represents the key for a phone number.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PhoneNumberKeyType extends Enumeration
{
    /**
     * Phone number key for assistant phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ASSISTANT_PHONE = 'AssistantPhone';

    /**
     * Phone number key for business fax number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BUSINESS_FAX = 'BusinessFax';

    /**
     * Phone number key for business phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BUSINESS_PHONE = 'BusinessPhone';

    /**
     * Phone number key for second business phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BUSINESS_PHONE_2 = 'BusinessPhone2';

    /**
     * Phone number key for callback.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CALLBACK = 'Callback';

    /**
     * Phone number key for car phone.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const CAR_PHONE = 'CarPhone';

    /**
     * Phone number key for company main phone.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMPANY_MAIN_PHONE = 'CompanyMainPhone';

    /**
     * Phone number key for home fax number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HOME_FAX = 'HomeFax';

    /**
     * Phone number key for home phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HOME_PHONE = 'HomePhone';

    /**
     * Phone number key for second home phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HOME_PHONE_2 = 'HomePhone2';

    /**
     * Phone number key for ISDN line.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ISDN = 'Isdn';

    /**
     * Phone number key for mobile phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const MOBILE_PHONE = 'MobilePhone';

    /**
     * Phone number key for other fax number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OTHER_FAX = 'OtherFax';

    /**
     * Phone number key for other phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OTHER_PHONE = 'OtherTelephone';

    /**
     * Phone number key for pager.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PAGER = 'Pager';

    /**
     * Phone number key for primary phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const PRIMARY_PHONE = 'PrimaryPhone';

    /**
     * Phone number key for radio phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RADIO_PHONE = 'RadioPhone';

    /**
     * Phone number key for telex.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const TELEX = 'Telex';

    /**
     * Phone number key for TTY TTD phone number.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const TTY_TTD_PHONE = 'TtyTtdPhone';
}
