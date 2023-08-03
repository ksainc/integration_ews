<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\BaseResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

use OCA\EWS\Components\EWS\Response;

/**
 * Base class for responses.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class BaseResponseMessageType extends Response
{
    /**
     * Contains the response messages for an Exchange Web Services request.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfResponseMessagesType
     */
    public $ResponseMessages;
}
