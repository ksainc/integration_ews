<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\UninstallAppType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to uninstall an app by its identifier.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class UninstallAppType extends BaseRequestType
{
    /**
     * Specifies the identifier of an app.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ID;
}
