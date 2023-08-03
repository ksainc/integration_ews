<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SearchRefinerItemType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a search refiner.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchRefinerItemType extends Type
{
    /**
     * Specifies a search refiner count.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $Count;

    /**
     * Specifies a search refiner name.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Name;

    /**
     * Search refiner token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Token;

    /**
     * Value of the search refinement.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Value;
}
