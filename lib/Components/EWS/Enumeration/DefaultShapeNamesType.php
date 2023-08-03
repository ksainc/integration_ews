<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the set of properties to return in an item or folder response.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class DefaultShapeNamesType extends Enumeration
{
    /**
     * Returns all the properties used by the Exchange Business Logic layer to
     * construct a folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ALL_PROPERTIES = 'AllProperties';

    /**
     * Returns a set of properties that are defined as the default for the item
     * or folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DEFAULT_PROPERTIES = 'Default';

    /**
     * Returns only the item or folder ID.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const ID_ONLY = 'IdOnly';
}
