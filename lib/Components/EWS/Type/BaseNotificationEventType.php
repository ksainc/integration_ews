<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\BaseNotificationEventType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a notification that no new activity has occurred in the mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class BaseNotificationEventType extends Type
{
    /**
     * Represents the last valid watermark for a subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Determine if we need a WatermarkType.
     */
    public $Watermark;
}
