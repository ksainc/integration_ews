<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DistinguishedFolderIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Identifies folders that can be referenced by name.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DistinguishedFolderIdType extends BaseFolderIdType
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
     * Identifies a default folder.
     *
     * This attribute is required.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DistinguishedFolderIdNameType
     */
    public $Id;

    /**
     * Identifies a primary SMTP address.
     *
     * Proxy addresses are not allowed.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
