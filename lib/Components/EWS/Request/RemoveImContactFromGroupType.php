<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RemoveImContactFromGroupType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to remove an instant messaging contact from an instant
 * messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RemoveImContactFromGroupType extends BaseRequestType
{
    /**
     * Identifies the contact to be removed.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ContactId;

    /**
     * Identifies the group to remove the contact from.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;
}
