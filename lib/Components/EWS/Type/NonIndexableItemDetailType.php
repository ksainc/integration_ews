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
 * Defines detail information about an item that cannot be indexed.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class NonIndexableItemDetailType extends Type
{
    /**
     * Contains the unique identifier and change key of an item in the Exchange
     * store.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorCode;

    /**
     * Describes the error that is returned in information about an item that
     * cannot be indexed.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ErrorDescription;

    /**
     * Indicates whether the item is partially indexed.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsPartiallyIndexed;

    /**
     * Indicates whether a previous attempt to index the item was unsuccessful.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsPermanentFailure;

    /**
     * Specifies a value used for sorting.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SortValue;

    /**
     * Represents the number of attempts that have been made to index the item.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $AttemptCount;

    /**
     * Contains the time and date at which the last attempt to index the item
     * was made.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastAttemptTime;

    /**
     * Specifies additional information about the hold status of a mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $AdditionalInfo;
}
