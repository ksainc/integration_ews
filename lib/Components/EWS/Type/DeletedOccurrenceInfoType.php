<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DeletedOccurrenceInfoType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a deleted occurrence of a recurring calendar item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DeletedOccurrenceInfoType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Start = null)
    {
        $this->Start = $Start;
    }
    /**
     * Represents the start time of a deleted occurrence of a recurring calendar
     * item.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $Start;
}
