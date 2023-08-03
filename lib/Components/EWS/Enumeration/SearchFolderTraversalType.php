<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SearchFolderTraversalType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the type of traversal to use for a folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SearchFolderTraversalType extends Enumeration
{
    /**
     * Consider both direct children as well as all children contained within
     * those children as well as the children's children, etc.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const DEEP = 'Deep';

    /**
     * Consider only direct children of the parent in question.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const SHALLOW = 'Shallow';
}
