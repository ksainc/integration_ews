<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BodyContentAttributedValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the body content of an item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BodyContentAttributedValueType extends Type
{
    /**
     * Specifies an array of attribution information for one or more of the
     * contacts or active directory recipients aggregated into the associated
     * persona.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPersonaAttributionsType
     */
    public $Attributions;

    /**
     * Specifies the value of a BodyContentAttributedValue element.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeResponseType
     */
    public $Value;
}
