<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SearchPageDirectionType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the direction for pagination in a search result.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SearchPageDirectionType extends Enumeration
{
    /**
     * Move to the next page in the result set.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NEXT = 'Next';

    /**
     * Move to the previous page in the result set.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PREVIOUS = 'Previous';
}
