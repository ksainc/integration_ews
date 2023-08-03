<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PermissionType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines the access that a user has to a folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PermissionType extends BasePermissionType
{
    /**
     * Represents the combination of permissions that a user has on a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PermissionLevelType
     */
    public $PermissionLevel;

    /**
     * Indicates whether a user has permission to read items within a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PermissionReadAccessType
     */
    public $ReadItems;
}
