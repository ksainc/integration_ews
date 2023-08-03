<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SearchResultType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the type of search to perform.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SearchResultType extends Enumeration
{
    /**
     * Return item preview information.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PREVIEW_ONLY = 'PreviewOnly';

    /**
     * Return the search statistics.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const STATISTICS_ONLY = 'StatisticsOnly';
}
