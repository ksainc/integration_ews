<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FolderResponseShapeType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the folder properties to include in a GetFolder, FindFolder, or
 * SyncFolderHierarchy response.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common ResponseShapeType.
 */
class FolderResponseShapeType extends Type
{
    /**
     * Identifies additional properties to return in a response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     */
    public $AdditionalProperties;

    /**
     * Identifies the basic configuration of properties to return in a response.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType
     */
    public $BaseShape;
}
