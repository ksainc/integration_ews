<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SortDirectionType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Identifies a sort order direction.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SortDirectionType extends Enumeration
{
    /**
     * Items are sorted in ascending order.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ASCENDING = 'Ascending';

    /**
     * Items are sorted in descending order.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DESCENDING = 'Descending';
}
