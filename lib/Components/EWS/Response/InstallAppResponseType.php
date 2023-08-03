<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\InstallAppResponseType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to an InstallApp request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class InstallAppResponseType extends ResponseMessageType
{
    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\Type\InstalledAppType
     *
     * @todo Update once documentation exists.
     */
    public $Extension;

    /**
     * Whether this is the first time the app is being installed.
     *
     * @since Exchange 2016
     *
     * @var boolean
     */
    public $WasFirstInstall;
}
