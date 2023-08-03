<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PermissionSetType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Contains all the permissions that are configured for a folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PermissionSetType extends Type
{
    /**
     * Contains the collection of permissions for a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPermissionsType
     */
    public $Permissions;

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
