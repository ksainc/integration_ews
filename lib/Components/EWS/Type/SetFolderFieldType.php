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
 * Represents an update that sets the value for a single property on a folder in
 * an UpdateFolder operation.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SetFolderFieldType extends FolderChangeDescriptionType
{
    /**
     * Represents a folder that primarily contains calendar items.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarFolderType
     */
    public $CalendarFolder;

    /**
     * Represents a Contacts folder in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContactsFolderType
     */
    public $ContactsFolder;

    /**
     * Identifies a folder to update.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderType
     */
    public $Folder;

    /**
     * Represents a search folder that is contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchFolderType
     */
    public $SearchFolder;

    /**
     * Represents a Tasks folder that is contained in a mailbox.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\TasksFolderType
     */
    public $TasksFolder;
}
