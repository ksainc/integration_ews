<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\AvailabilityProxyRequestType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines whether a proxy request is a cross-site or a cross-forest request.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class AvailabilityProxyRequestType extends Enumeration
{
    /**
     * Indicates that this request is cross-forest.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CROSS_FOREST = 'CrossForest';

    /**
     * Indicates that this request is cross-site.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CROSS_SITE = 'CrossSite';
}
