<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RemoveDelegateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to remove delegates from a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RemoveDelegateType extends BaseDelegateType
{
    /**
     * Contains an array of delegate users to remove from a principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfUserIdType
     */
    public $UserIds;
}
