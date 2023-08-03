<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ConstantValueType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies a constant value in a restriction.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConstantValueType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Value = null)
    {
        $this->Value = $Value;
    }
    /**
     * Specifies the value to compare in the restriction.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Value;
}
