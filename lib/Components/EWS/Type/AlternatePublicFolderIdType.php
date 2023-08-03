<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AlternatePublicFolderIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes a public folder identifier to convert to another identifier format.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AlternatePublicFolderIdType extends AlternateIdBaseType
{
    /**
     * Contains the public folder identifier to convert.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    public $FolderId;
}
