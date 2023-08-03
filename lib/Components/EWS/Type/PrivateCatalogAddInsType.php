<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PrivateCatalogAddInsType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Undocumented.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Update once documentation exists.
 */
class PrivateCatalogAddInsType extends Type
{
    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $ProductId;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\AddInStateType
     *
     * @todo Update once documentation exists.
     */
    public $State;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $Version;
}
