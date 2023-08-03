<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\PermissionReadAccessType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Indicates whether a user has permission to read items within a folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class PermissionReadAccessType extends Enumeration
{
    /**
     * Indicates that the user has permission to read all items in the folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const FULL_DETAILS = 'FullDetails';

    /**
     * Indicates that the user does not have permission to read items in the
     * folder.
     *
     * @since Exchange 2007 SP1
     *
     * @var string
     */
    const NONE = 'None';
}
