<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ChangeDescriptionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for changes to individual properties.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class ChangeDescriptionType extends Type
{
    /**
     * Identifies extended MAPI properties to set.
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
