<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DistinguishedGroupByType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents standard groupings for FindItem queries.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DistinguishedGroupByType extends BaseGroupByType
{
    /**
     * Represents the standard grouping and aggregating mechanisms for a grouped
     * FindItem operation.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\StandardGroupByType
     */
    public $StandardGroupBy;
}
