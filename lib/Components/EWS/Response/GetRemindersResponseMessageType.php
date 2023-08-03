<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetRemindersResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the response to a GetReminders request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetRemindersResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the reminders returned in the response.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRemindersType
     */
    public $Reminders;
}
