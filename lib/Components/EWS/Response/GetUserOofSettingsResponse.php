<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetUserOofSettingsResponse.
 */

namespace OCA\EWS\Components\EWS\Response;

use OCA\EWS\Components\EWS\Response;

/**
 * Represents the response message and the Out of Office (OOF) settings for a
 * user.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetUserOofSettingsResponse extends Response
{
    /**
     * Contains a value that identifies to whom external OOF messages are sent.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ExternalAudience
     */
    public $AllowExternalOof;

    /**
     * Contains the OOF settings.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\UserOofSettings
     */
    public $OofSettings;

    /**
     * Provides descriptive information about the response status.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType
     */
    public $ResponseMessage;
}
