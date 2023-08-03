<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ConflictResultsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Indicates the number of conflicts in an UpdateItem Operation response.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConflictResultsType extends Type
{
    /**
     * Contains the number of conflicts in an UpdateItem Operation response.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Count;
}
