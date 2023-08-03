<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\ExpandDLType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to expand a distribution list.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ExpandDLType extends BaseRequestType
{
    /**
     * Identifies a fully resolved e-mail address of a distribution list.
     *
     * This mailbox represents the distribution list to expand.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
