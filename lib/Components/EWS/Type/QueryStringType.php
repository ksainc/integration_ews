<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\QueryStringType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a mailbox query string based on Advanced Query Syntax (AQS).
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a base string class?
 */
class QueryStringType extends Type
{
    /**
     * Text value of the query string.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @link http://msdn.microsoft.com/en-us/library/ee693615(v=exchg.150).aspx
     */
    public $_;

    /**
     * Indicates that the cache should be reset.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $ResetCache;

    /**
     * Indicates that deleted items should be returned.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $ReturnDeletedItems;

    /**
     * Indicates that highlighted terms should be returned.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $ReturnHighlightTerms;
}
