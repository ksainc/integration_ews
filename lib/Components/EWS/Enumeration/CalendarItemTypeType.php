<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\CalendarItemTypeType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the type of a calendar item.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class CalendarItemTypeType extends Enumeration
{
    /**
     * The item is an exception to a recurring calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EXCEPTION = 'Exception';

    /**
     * The item is an occurrence of a recurring calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OCCURRENCE = 'Occurrence';

    /**
     * The item is master for a set of recurring calendar items.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const RECURRING_MASTER = 'RecurringMaster';

    /**
     * The item is not associated with a recurring calendar item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SINGLE = 'Single';
}
