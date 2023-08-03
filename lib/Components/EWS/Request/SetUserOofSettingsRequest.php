<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\SetUserOofSettingsRequest.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the arguments used to set a mailbox user's Out of Office (OOF)
 * settings.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SetUserOofSettingsRequest extends BaseRequestType
{
    /**
     * Identifies the mailbox user for a SetUserOofSettings or
     * GetUserOofSettings request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Mailbox;

    /**
     * Specifies the OOF settings.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\UserOofSettings
     */
    public $UserOofSettings;
}
