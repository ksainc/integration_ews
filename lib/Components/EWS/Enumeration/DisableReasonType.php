<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\DisableReasonType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies the reason for disabling an app.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DisableReasonType extends Enumeration
{
    /**
     * To improve mobile client performance.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MOBILE_CLIENT_PERFORMANCE = 'MobileClientPerformance';

    /**
     * No reason given.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NO_REASON = 'NoReason';

    /**
     * To improve email client performance.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const OUTLOOK_CLIENT_PERFORMANCE = 'OutlookClientPerformance';

    /**
     * To improve Web app client performance.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const OWA_CLIENT_PERFORMANCE = 'OWAClientPerformance';
}
