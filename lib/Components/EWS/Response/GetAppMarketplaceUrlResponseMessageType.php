<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetAppMarketplaceUrlResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetAppMarketplaceUrl request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetAppMarketplaceUrlResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the URL for the app marketplace.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $AppMarketplaceUrl;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $ConnectorsManagementUrl;
}
