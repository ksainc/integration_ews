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

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the parameters that define a search folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchParametersType extends Type
{
    /**
     * Represents the collection of folders that will be mined to determine the
     * contents of a search folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfBaseFolderIdsType
     */
    public $BaseFolderIds;

    /**
     * Represents the restriction or query that is used to filter items or
     * folders in FindItem/FindFolder and search folder operations.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\RestrictionType
     */
    public $Restriction;

    /**
     * Describes how a search folder traverses the folder hierarchy.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SearchFolderTraversalType
     */
    public $Traversal;
}
