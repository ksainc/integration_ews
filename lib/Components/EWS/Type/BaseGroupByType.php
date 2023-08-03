<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BaseGroupByType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for item ordering.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class BaseGroupByType extends Type
{
    /**
     * Determines the order of the groups in the grouped item array that is
     * returned in the response.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SortDirectionType
     */
    public $Order;
}
