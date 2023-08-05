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
 * Defines how to construct what is displayed for a contact.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class FileAsMappingType extends Enumeration
{
    /**
     * File as mapping for "company".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMPANY = 'Company';

    /**
     * File as mapping for "last name, first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMPANY_LAST_COMMA_FIRST = 'CompanyLastCommaFirst';

    /**
     * File as mapping for "company last name first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMPANY_LAST_FIRST = 'CompanyLastFirst';

    /**
     * File as mapping for "company last name first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const COMPANY_LAST_SPACE_FIRST = 'CompanyLastSpaceFirst';

    /**
     * File as mapping for "display name".
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DISPLAY_NAME = 'DisplayName';

    /**
     * File as mapping to use when no mapping is defined.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const EMPTY_MAPPING = 'Empty';

    /**
     * File as mapping for "first name".
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FIRST_NAME = 'FirstName';

    /**
     * File as mapping for "first name last name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FIRST_SPACE_LAST = 'FirstSpaceLast';

    /**
     * File as mapping for "last name, first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_COMMA_FIRST = 'LastCommaFirst';

    /**
     * File as mapping for "last name, first name company".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_COMMA_FIRST_COMPANY = 'LastCommaFirstCompany';

    /**
     * File as mapping for "last name first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_FIRST = 'LastFirst';

    /**
     * File as mapping for "last name first name company".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_FIRST_COMPANY = 'LastFirstCompany';

    /**
     * File as mapping for "last name first name middle name suffix".
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const LAST_FIRST_MIDDLE_SUFFIX = 'LastFirstMiddleSuffix';

    /**
     * File as mapping for "last name first name suffix".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_FIRST_SUFFIX = 'LastFirstSuffix';

    /**
     * File as mapping for "last name".
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const LAST_NAME = 'LastName';

    /**
     * File as mapping for "last name first name".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_SPACE_FIRST = 'LastSpaceFirst';

    /**
     * File as mapping for "last name first name company".
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LAST_SPACE_FIRST_COMPANY = 'LastSpaceFirstCompany';

    /**
     * File as mapping to use when no mapping is desired.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NONE = 'None';
}
