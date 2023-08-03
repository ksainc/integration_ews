<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TransitionTargetType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Specifies the target of the time zone transition.
 *
 * The target is either a time zone period or a group of time zone transitions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TransitionTargetType extends StringType
{
    /**
     * Indicates whether the time zone transition target is a time zone period
     * or of a group of time zone transitions.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\TransitionTargetKindType
     */
    public $Kind;
}
