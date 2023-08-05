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
 * Defines the result of a SearchMailboxes request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchMailboxesResultType extends Type
{
    /**
     * Contains a list of mailboxes and associated queries for discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfMailboxQueriesType
     */
    public $SearchQueries;

    /**
     * Contains the type of search to perform.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SearchResultType
     */
    public $ResultType;

    /**
     * Specifies the total number of items in a search result.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $ItemCount;

    /**
     * Specifies the total size of one or more mailbox items.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $Size;

    /**
     * Specifies the number of pages returned in a search result pagination.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $PageItemCount;

    /**
     * Specifies the number of items to return in a search result pagination.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $PageItemSize;

    /**
     * Specifies a list of one or more KeywordStat elements.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfKeywordStatisticsSearchResultsType
     */
    public $KeywordStats;

    /**
     * Specifies a list of items available for preview.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSearchPreviewItemsType
     */
    public $Items;

    /**
     * Specifies a list of mailboxes that failed on search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFailedSearchMailboxesType
     */
    public $FailedMailboxes;

    /**
     * Specifies a list of one or more refiners.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSearchRefinerItemsType
     */
    public $Refiners;

    /**
     * Specifies a list of one or more mailbox stats.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfMailboxStatisticsItemsType
     */
    public $MailboxStats;
}
