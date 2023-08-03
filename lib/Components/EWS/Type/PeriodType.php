<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PeriodType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the name, time offset, and unique identifier for a specific stage of
 * the time zone.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PeriodType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Name = null, string $Bias = null, string $Id = null)
    {
        $this->Name = $Name;
        $this->Bias = $Bias;
        $this->Id = $Id;
    }

    /**
     * An xs:duration value that represents the time offset from Coordinated
     * Universal Time (UTC) for the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Bias;

    /**
     * A string value that represents the identifier for the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Id;

    /**
     * A string value that represents the descriptive name of the period.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Name;
}
