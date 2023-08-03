<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\ConferenceType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the type of conferencing that is performed with a calendar item.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ConferenceType extends Enumeration
{
    /**
     * The meeting is offline.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    const CHAT = 2;

    /**
     * The meeting is an Internet meeting.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    const NET_MEETING = 0;

    /**
     * The meeting is an Internet show (such as a webinar).
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    const NET_SHOW = 1;
}
