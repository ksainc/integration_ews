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

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines a search for contact items based on alphabetical display names.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ContactsViewType extends BasePagingType
{
    /**
     * Defines the last name in the contacts list to return in the response.
     *
     * If the FinalName attribute is omitted, the response will contain all
     * subsequent contacts in the specified sort order. If the specified final
     * name is not in the contacts list, the next alphabetical name as defined
     * by the cultural context will be excluded.
     *
     * For example, if FinalName="Name", but Name is not in the contacts list,
     * contacts that have display names of Name1 or NAME will not be included.
     *
     * This attribute is optional.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $FinalName;

    /**
     * Defines the first name in the contacts list to return in the response.
     *
     * If the specified initial name is not in the contacts list, the next
     * alphabetical name as defined by the cultural context will be returned,
     * except if the next name comes after FinalName.
     *
     * If the InitialName attribute is omitted, the response will contain a list
     * of contacts that starts with the first name in the contact list.
     *
     * This attribute is optional.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $InitialName;
}
