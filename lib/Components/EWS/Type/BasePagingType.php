<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BasePagingType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for defining how results should be paged.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class BasePagingType extends Type
{
    /**
     * Describes the maximum number of items or conversations to return in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MaxEntriesReturned;
}
