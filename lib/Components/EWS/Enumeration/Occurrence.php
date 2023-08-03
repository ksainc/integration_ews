<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\Occurrence.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the occurrence of the day of the week in a month.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class Occurrence extends Enumeration
{
    /**
     * The first occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FIRST_FROM_BEGINNING = 1;

    /**
     * The first occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FIRST_FROM_END = -1;

    /**
     * The fourth occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FOURTH_FROM_BEGINNING = 4;

    /**
     * The fourth occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const FOURTH_FROM_END = -4;

    /**
     * The second occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const SECOND_FROM_BEGINNING = 2;

    /**
     * The second occurrence of the specified day of the week from the end of
     * the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const SECOND_FROM_END = -2;

    /**
     * The third occurrence of the specified day of the week from the beginning
     * of the month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const THIRD_FROM_BEGINNING = 3;

    /**
     * The third occurrence of the specified day of the week from the end of the
     * month.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    const THIRD_FROM_END = -3;
}
