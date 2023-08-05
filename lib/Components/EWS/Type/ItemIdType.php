<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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
 * Contains the unique identifier and change key of an item in the Exchange
 * store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ItemIdType extends BaseItemIdType
{
    /*Constructor method with arguments*/
    public function __construct(string $Id = null, string $ChangeKey = null)
    {
        $this->Id = $Id;
        $this->ChangeKey = $ChangeKey;
    }
    
    /**
     * Identifies a specific version of an item.
     *
     * A ChangeKey is required for the following scenarios:
     * - The UpdateItem element requires a ChangeKey if the ConflictResolution
     *   attribute is set to AutoResolve. AutoResolve is a default value. If the
     *   ChangeKey attribute is not included, the response will return a
     *   ResponseCode value equal to ErrorChangeKeyRequired.
     * - The SendItem element requires a ChangeKey to test whether the attempted
     *   operation will act upon the most recent version of an item. If the
     *   ChangeKey attribute is not included in the ItemId or if the ChangeKey
     *   is empty, the response will return a ResponseCode value equal to
     *   ErrorStaleObject.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ChangeKey;

    /**
     * Identifies a specific item in the Exchange store.
     *
     * Id is case-sensitive; therefore, comparisons between Ids must be
     * case-sensitive or binary.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Id;
}
