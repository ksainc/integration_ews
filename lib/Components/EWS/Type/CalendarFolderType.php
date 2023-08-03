<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\CalendarFolderType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a folder that primarily contains calendar items.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarFolderType extends BaseFolderType
{
    /**
     * Contains all the configured permissions for a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\CalendarPermissionSetType
     */
    public $PermissionSet;

    /**
     * Indicates the permissions that the user has for the calendar data that is
     * being shared.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarPermissionReadAccessType
     */
    public $SharingEffectiveRights;
}
