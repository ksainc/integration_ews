<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\CalendarPermissionSetType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Contains all the permissions that are configured for a calendar folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarPermissionSetType extends Type
{
    /**
     * Contains an array of calendar permissions for a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfCalendarPermissionsType
     */
    public $CalendarPermissions;

    /**
     * Contains an array of unknown entries that cannot be resolved against the
     * Active Directory directory service.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfUnknownEntriesType
     */
    public $UnknownEntries;
}
