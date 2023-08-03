<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SearchFolderType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a search folder that is contained in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchFolderType extends FolderType
{
    /**
     * Contains the parameters that define a search folder.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SearchParametersType
     */
    public $SearchParameters;
}
