<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\SetImGroupType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to change the display name of an instant messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class SetImGroupType extends BaseRequestType
{
    /**
     * Identifies the instant messaging group to be updated.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;

    /**
     * Contains the updated display name of an instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $NewDisplayName;
}
