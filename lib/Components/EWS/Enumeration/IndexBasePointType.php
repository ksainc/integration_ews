<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\IndexBasePointType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines whether a page of items or conversations will start from the
 * beginning or the end of a set.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class IndexBasePointType extends Enumeration
{
    /**
     * The paged view starts at the beginning of the found conversation or item
     * set.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BEGINNING = 'Beginning';

    /**
     * The paged view starts at the end of the found conversation or item set.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const END = 'End';
}
