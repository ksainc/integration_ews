<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\AffectedTaskOccurrencesType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines whether a task instance or a task master is deleted by a DeleteItem
 * operation.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class AffectedTaskOccurrencesType extends Enumeration
{
    /**
     * A delete item request deletes the master task, and therefore all
     * recurring tasks that are associated with the master task.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ALL = 'AllOccurrences';

    /**
     * A delete item request deletes only specific occurrences of a task.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SPECIFIED = 'SpecifiedOccurrenceOnly';
}
