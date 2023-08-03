<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SetUserOofSettingsResponse.
 */

namespace OCA\EWS\Components\EWS\Response;

use OCA\EWS\Components\EWS\Response;

/**
 * Defines the result of a SetUserOofSettingsRequest message attempt.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SetUserOofSettingsResponse extends Response
{
    /**
     * Provides descriptive information about the response status.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType
     */
    public $ResponseMessage;
}
