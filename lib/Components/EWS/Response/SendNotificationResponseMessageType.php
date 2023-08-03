<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SendNotificationResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single SendNotification operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SendNotificationResponseMessageType extends ResponseMessageType
{
    /**
     * Contains information about the subscription and the events that have
     * occurred since the last notification.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NotificationType
     */
    public $Notification;
}
