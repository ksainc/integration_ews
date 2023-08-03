<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SearchableMailboxType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a mailbox that may be searched.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SearchableMailboxType extends Type
{
    /**
     * Defines the display name of the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Contains the external email address of the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ExternalEmailAddress;

    /**
     * Specifies the globally unique identifier of the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Guid;

    /**
     * Indicates whether the mailbox is external to the organization.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsExternalMailbox;

    /**
     * Specifies a Boolean value that indicates whether the entity is a
     * distribution group or a mailbox.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsMembershipGroup;

    /**
     * Specifies the primary Simple Mail Transfer Protocol (SMTP) address of the
     * mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $PrimarySmtpAddress;

    /**
     * Specifies the reference identifier for the mailbox.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ReferenceId;
}
