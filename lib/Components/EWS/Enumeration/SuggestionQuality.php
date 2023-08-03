<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SuggestionQuality.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the quality of the day for containing quality suggested meeting
 * times.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SuggestionQuality extends Enumeration
{
    /**
     * Indicates that the suggested meeting time is excellent.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const EXCELLENT = 'Excellent';

    /**
     * Indicates that the suggested meeting time is fair.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const FAIR = 'Fair';

    /**
     * Indicates that the suggested meeting time is good.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const GOOD = 'Good';

    /**
     * Indicates that the suggested meeting time is poor.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const POOR = 'Poor';
}
