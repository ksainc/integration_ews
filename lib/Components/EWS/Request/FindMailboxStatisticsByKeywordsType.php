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
 * Defines a request to search for mailbox statistics by keyword.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class FindMailboxStatisticsByKeywordsType extends BaseRequestType
{
    /**
     * Specifies the date that the message was sent.Specifies an array of
     * recipients of a message.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $FromDate;

    /**
     * Specifies whether to include the personal archive in the search.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IncludePersonalArchive;

    /**
     * Specifies whether to include items that cannot be searched.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IncludeUnsearchableItems;

    /**
     * Specifies keywords for a search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $Keywords;

    /**
     * Contains the language used for the search query.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Language;

    /**
     * Contains an array of mailboxes affected by the hold.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfUserMailboxesType
     */
    public $Mailboxes;

    /**
     * Specifies an array of messages to search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSearchItemKindsType
     */
    public $MessageTypes;

    /**
     * Specifies an array of recipients of a message.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSmtpAddressType
     */
    public $Recipients;

    /**
     * Specifies whether to search in deleted items.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $SearchDumpster;

    /**
     * Specifies an array of SMTP addresses.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSmtpAddressType
     */
    public $Senders;

    /**
     * Specifies the date that the message was received.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ToDate;
}
