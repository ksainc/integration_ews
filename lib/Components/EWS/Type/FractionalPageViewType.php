<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FractionalPageViewType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Describes where the paged view starts and the maximum number of folders
 * returned in a FindFolder request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FractionalPageViewType extends BasePagingType
{
    /*Constructor method with arguments*/
    public function __construct(int $denominator = 0, int $numerator = 0, int $limit = 512)
    {
        $this->Denominator = $denominator;
        $this->Numerator = $numerator;
        $this->MaxEntriesReturned = $limit;
    }

    /**
     * Represents the denominator of the fractional offset from the start of the
     * total number of folders in the result set.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Denominator;

    /**
     * Represents the numerator of the fractional offset from the start of the
     * result set.
     *
     * The numerator must be equal to or less than the denominator.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Numerator;
}
