<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\TransitionTargetKindType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the kind of a transition.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class TransitionTargetKindType extends Enumeration
{
    /**
     * Specifies that the time zone transition target is a group of time zone
     * transitions.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const GROUP = 'Group';

    /**
     * Specifies that the time zone transition target is a time zone period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const PERIOD = 'Period';
}
