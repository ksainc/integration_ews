<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\FindPeopleType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a set of data used in a FindPeople request.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class FindPeopleType extends BaseRequestType
{
    /**
     * Specifies a value that is applied to a set of Persona properties.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\RestrictionType
     */
    public $AggregationRestriction;

    /**
     * Specifies the context properties of the contact.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfContextProperty
     */
    public $Context;

    /**
     * Describes how paged conversation or item information is returned.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\IndexedPageViewType
     */
    public $IndexedPageItemView;

    /**
     * Identifies the folder in which to search.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     */
    public $ParentFolderId;

    /**
     * Specifies the set of persona properties.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaResponseShapeType
     */
    public $PersonaShape;

    /**
     * Specifies the source data for the query.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPeopleQuerySource
     */
    public $QuerySources;

    /**
     * Contains a mailbox query string based on Advanced Query Syntax (AQS).
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\QueryStringType
     */
    public $QueryString;

    /**
     * Represents the restriction or query that is used to filter items.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\RestrictionType
     */
    public $Restriction;

    /**
     * Internal use only.
     *
     * @since Exchange 2016
     *
     * @var boolean
     */
    public $SearchPeopleSuggestionIndex;

    /**
     * Defines how items are sorted.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfFieldOrdersType
     */
    public $SortOrder;

    /**
     * Specifies the query string for topic searches.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    public $TopicQueryString;
}
