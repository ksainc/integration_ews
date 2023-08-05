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
 * Defines a SearchMailboxes request.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SearchMailboxesType extends BaseRequestType
{
    /**
     * Indicates whether the search result should remove duplicate items.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $Deduplication;

    /**
     * Contains the language used for the search query.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Language;

    /**
     * Contains the direction for pagination in the search result.
     *
     * The value is Previous or Next.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SearchPageDirectionType
     */
    public $PageDirection;

    /**
     * Specifies the reference for a page item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $PageItemReference;

    /**
     * Contains the number of items to be returned in a single page for a search
     * result.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $PageSize;

    /**
     * Contains the requested property set to be returned in a discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PreviewItemResponseShapeType
     */
    public $PreviewItemResponseShape;

    /**
     * Contains the type of search to perform.
     *
     * The type of search can be statistics only or preview only.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SearchResultType
     */
    public $ResultType;

    /**
     * Contains a list of mailboxes and associated queries for discovery search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfMailboxQueriesType
     */
    public $SearchQueries;

    /**
     * Contains an item property used for sorting the search result.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\FieldOrderType
     */
    public $SortBy;
}
