<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhoneNumberAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a phone number and its associated attributions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PhoneNumberAttributedValueType extends Type
{
    /**
     * Specifies an array of attributions for the Value element.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfValueAttributionsType
     */
    public $Attributions;

    /**
     * Specifies a phone number and type information.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaPhoneNumberType
     */
    public $Value;
}
