<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AddressListIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the identifier of an address list.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AddressListIdType extends Type
{
    /**
     * A string address list identifier.
     *
     * This attribute is required.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;
}
