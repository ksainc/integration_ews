<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\CalendarPermissionType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Defines the access that a user has to a Calendar folder.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class CalendarPermissionType extends BasePermissionType
{
    /**
     * Represents the permission level that a user has on a Calendar folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarPermissionLevelType
     */
    public $CalendarPermissionLevel;

    /**
     * Indicates whether a user has permission to read items within a folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\CalendarPermissionReadAccessType
     */
    public $ReadItems;
}
