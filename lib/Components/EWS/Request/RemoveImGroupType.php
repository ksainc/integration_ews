<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RemoveImGroupType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to remove an instant messaging group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RemoveImGroupType extends BaseRequestType
{
    /**
     * Identifies the group to be removed.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;
}
