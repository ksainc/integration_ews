<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\AddImContactToGroup.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to add an existing instant messaging contact to an instant
 * messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class AddImContactToGroup extends BaseRequestType
{
    /**
     * Uniquely identifies a contact.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ContactId;

    /**
     * Uniquely identifies a group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;
}
