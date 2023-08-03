<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetDelegateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get information about delegates to a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetDelegateType extends BaseDelegateType
{
    /**
     * Indicates whether the response contains permission settings for each
     * delegate user.
     *
     * @since Exchange 2007 SP1
     *
     * @var boolean
     */
    public $IncludePermissions;

    /**
     * Contains an array of delegate users to get from a principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfUserIdType
     */
    public $UserIds;
}
