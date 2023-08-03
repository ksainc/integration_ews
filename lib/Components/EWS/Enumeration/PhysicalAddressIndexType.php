<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\PhysicalAddressIndexType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents the display types for physical addresses.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PhysicalAddressIndexType extends Enumeration
{
    /**
     * Address index for business.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BUSINESS = 'Business';

    /**
     * Address index for home.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HOME = 'Home';

    /**
     * Address index for none.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * Address index for other.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const OTHER = 'Other';
}
