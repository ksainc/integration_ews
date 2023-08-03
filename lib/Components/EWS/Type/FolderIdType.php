<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FolderIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents the identifier of the parent folder that contains the item or
 * folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FolderIdType extends BaseFolderIdType
{
    /*Constructor method with arguments*/
    public function __construct(string $Id = null, string $ChangeKey = null)
    {
        $this->Id = $Id;
        $this->ChangeKey = $ChangeKey;
    }
    
    /**
     * Contains a string that identifies a version of a folder that is
     * identified by the Id attribute.
     *
     * This attribute is optional. Use this attribute to make sure that the
     * correct version of a folder is used.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ChangeKey;

    /**
     * Contains a string that identifies a folder in the Exchange store.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Id;
}
