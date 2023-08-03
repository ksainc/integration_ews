<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RemoveContactFromImListType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to remove an instant messaging contact from all instant
 * messaging groups.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RemoveContactFromImListType extends BaseRequestType
{
    /**
     * Uniquely identifies a contact.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ContactId;
}
