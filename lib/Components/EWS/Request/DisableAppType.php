<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\DisableAppType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to disable an app.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class DisableAppType extends BaseRequestType
{
    /**
     * Specifies the reason for disabling an app.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DisableReasonType
     */
    public $DisableReason;

    /**
     * Specifies the identifier of an item.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ID;
}
