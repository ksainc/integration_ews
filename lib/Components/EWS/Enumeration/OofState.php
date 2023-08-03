<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\OofState.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents a user's Out of Office (OOF) state.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class OofState extends Enumeration
{
    /**
     * The user's OOF setting is currently disabled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DISABLED = 'Disabled';

    /**
     * The user's OOF setting is currently enabled.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ENABLED = 'Enabled';

    /**
     * The user's OOF setting is scheduled to start at a specific date and end
     * at another specific date.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SCHEDULED = 'Scheduled';
}
