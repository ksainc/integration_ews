<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AlternatePublicFolderItemIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes a public folder item identifier to convert to another identifier
 * format.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AlternatePublicFolderItemIdType extends AlternatePublicFolderIdType
{
    /**
     * Identifier the public folder item to convert.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $ItemId;
}
