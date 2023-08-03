<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\PreviewItemBaseShapeType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the type of preview to be returned for an item.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PreviewItemBaseShapeType extends Enumeration
{
    /**
     * Indicates that only selected properties are shown.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const COMPACT = 'Compact';

    /**
     * Indicates that all properties are shown.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DEFAULT_SHAPE = 'Default';
}
