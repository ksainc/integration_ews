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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to set a mailbox hold policy on mailboxes.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SetHoldOnMailboxesType extends BaseRequestType
{
    /**
     * Indicates the type of action for the hold.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\HoldActionType
     */
    public $ActionType;

    /**
     * Indicates whether the search result should remove duplicate items.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $Deduplication;

    /**
     * Contains the mailbox hold identifier.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $HoldId;

    /**
     * Specifies the identity of a hold that preserves the mailbox items.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $InPlaceHoldIdentity;

    /**
     * Indicates whether to include items that cannot be indexed.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IncludeNonIndexableItems;

    /**
     * Specifies the amount of time to hold content that matches the mailbox
     * query.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $ItemHoldPeriod;

    /**
     * Contains the language used for the search query.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Language;

    /**
     * Contains a list of mailboxes affected by the hold.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $Mailboxes;

    /**
     * Contains the search query for the hold.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Query;
}
