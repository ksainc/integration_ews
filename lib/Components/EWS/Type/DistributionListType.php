<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\DistributionListType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a distribution list.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class DistributionListType extends ItemType
{
    /**
     * Describes whether the contact is located in the Exchange store or in
     * Active Directory Domain Services (AD DS).
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ContactSourceType
     */
    public $ContactSource;

    /**
     * Defines the display name of a distribution list.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Represents how a distribution list is filed in the Contacts folder.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $FileAs;

    /**
     * Contains a list of members of the distribution list.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\MembersListType
     */
    public $Members;
}
