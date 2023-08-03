<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ImportanceChoicesType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Describes the importance of an item or the aggregated importance of all items
 * in a conversation in the current folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ImportanceChoicesType extends Enumeration
{
    /**
     * High importance level.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HIGH = 'High';

    /**
     * Low importance level.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const LOW = 'Low';

    /**
     * Normal importance level.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NORMAL = 'Normal';
}
