<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\AddNewImContactToGroup.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add a new instant messaging contact to an instant
 * messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class AddNewImContactToGroup extends BaseRequestType
{
    /**
     * Contains the display name of a new instant messaging group contact or the
     * display name of a new instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Unique identifier of a group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;

    /**
     * Contains the instant messaging address of a new contact that will be
     * added to an instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $ImAddress;
}
