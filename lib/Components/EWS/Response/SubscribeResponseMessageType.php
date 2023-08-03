<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SubscribeResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single Subscribe Operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SubscribeResponseMessageType extends ResponseMessageType
{
    /**
     * Represents the identifier for a subscription.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SubscriptionId;

    /**
     * Watermark property
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Determine if we need a WatermarkType.
     */
    public $Watermark;
}
