<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TransitionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a time zone transition.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Alter RecurringDateTransitionType to extend this class.
 */
class TransitionType extends Type
{
    /**
     * Specifies the Period or TransitionsGroup that is the target of the time
     * zone transition.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TransitionTargetType
     */
    public $To;
}
