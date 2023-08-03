<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PostalAddressAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an instance of an array of postal addresses and their associated
 * attributions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PostalAddressAttributedValueType extends Type
{
    /**
     * Specifies an array of attributions for its associated Value element.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfValueAttributionsType
     */
    public $Attributions;

    /**
     * Specifies information associated with a postal address.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaPostalAddressType
     */
    public $Value;
}
