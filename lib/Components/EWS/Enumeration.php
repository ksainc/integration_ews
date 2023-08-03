<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration.
 */

namespace OCA\EWS\Components\EWS;

/**
 * Base class for Exchange Web Service Enumerations.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class Enumeration extends Type
{
    /**
     * Element value.
     *
     * @deprecated 1.0.0
     *   This property will be removed in a future release and should not be
     *   used. Instead, you should reference the constants implemented in the
     *   class directly.
     *
     * @var string
     */
    public $_;

    /**
     * Returns the value of this enumeration as a string..
     *
     * @return string
     *
     * @suppress PhanDeprecatedProperty
     */
    public function __toString()
    {
        return $this->_;
    }
}
