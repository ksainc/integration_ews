<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\BaseDelegateType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Base class for delegate requests.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
abstract class BaseDelegateType extends BaseRequestType
{
    /**
     * Identifies the principal's mailbox.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
