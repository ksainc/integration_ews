<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\BaseDelegateResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Base class for delegate response messages.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
abstract class BaseDelegateResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the response messages for an Exchange Web Services delegate
     * management request.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfDelegateUserResponseMessageType
     */
    public $ResponseMessages;
}
