<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetUserOofSettingsRequest.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the arguments used to get a mailbox user's Out of Office (OOF)
 * settings.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetUserOofSettingsRequest extends BaseRequestType
{
    /**
     * Identifies the mailbox user for the request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;
}
