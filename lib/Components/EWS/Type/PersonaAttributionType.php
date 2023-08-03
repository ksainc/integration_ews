<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PersonaAttributionType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an instance in an array of attributes for a Persona.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PersonaAttributionType extends Type
{
    /**
     * Defines the display name of a folder, contact, distribution list,
     * delegate user, or rule.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Contains the identifier and change key of a folder.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $FolderId;

    /**
     * Specifies a string that uniquely identifies an app or an attribution in a
     * persona.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;

    /**
     * Contains a Boolean value that indicates whether the underlying contact or
     * Active Directory recipient should be hidden or displayed as part of the
     * persona.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsHidden;

    /**
     * Specifies a Boolean value that indicates whether the underlying contact
     * or Active Directory recipient is a quick contact.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsQuickContact;

    /**
     * Specifies whether the underlying contact or Active Directory recipient
     * can be written to.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsWritable;

    /**
     * Specifies the identifier of the contact or Active Directory recipient.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $SourceId;
}
