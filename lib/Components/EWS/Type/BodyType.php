<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BodyType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Specifies the body of an item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BodyType extends StringType
{
    /*Constructor method with arguments*/
    public function __construct(string $Type, string $Contents)
    {
        $this->BodyType = $Type;
        $this->_ = $Contents;
    }

    /**
     * Specifies the type of the body.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeType
     */
    public $BodyType;

    /**
     * Boolean value that indicates whether the body is truncated.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsTruncated;
}
