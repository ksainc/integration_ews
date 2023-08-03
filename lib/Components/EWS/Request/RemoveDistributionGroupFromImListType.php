<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\RemoveDistributionGroupFromImListType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to remove a specific instant messaging distribution list
 * group.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class RemoveDistributionGroupFromImListType extends BaseRequestType
{
    /**
     * Identifies the distribution group to be removed.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $GroupId;
}
