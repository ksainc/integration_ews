<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SearchExpressionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the substituted element within a restriction.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Determine which classes need to extend this.
 */
abstract class SearchExpressionType extends Type
{
    /**
     * Identifies MAPI properties.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToExtendedFieldType
     */
    public $ExtendedFieldURI;

    /**
     * Identifies frequently referenced properties by URI.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToUnindexedFieldType
     */
    public $FieldURI;

    /**
     * Identifies individual members of a dictionary.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\PathToIndexedFieldType
     */
    public $IndexedFieldURI;
}
