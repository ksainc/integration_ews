<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MemberType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a member of a distribution list.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MemberType extends Type
{
    /**
     * Provides a unique identifier for the distribution list member.
     *
     * This attribute is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Key;

    /**
     * Represents the e-mail address of the distribution list member.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;

    /**
     * Provides information about the status of a distribution list member.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MemberStatusType
     */
    public $Status;
}
