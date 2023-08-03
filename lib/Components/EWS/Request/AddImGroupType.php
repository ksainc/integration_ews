<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\AddImGroupType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add a new instant messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class AddImGroupType extends BaseRequestType
{
    /**
     * Display name of the instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;
}
