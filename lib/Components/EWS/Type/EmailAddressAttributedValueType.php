<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\EmailAddressAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an instance of an array of email addresses and their associated
 * attributions.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class EmailAddressAttributedValueType extends Type
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
     * Specifies the value of an EmailAddress associated with an attributions array.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Value;
}
