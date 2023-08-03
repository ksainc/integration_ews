<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetNonIndexableItemStatisticsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to retrieve non-indexable item statistics.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetNonIndexableItemStatisticsType extends BaseRequestType
{
    /**
     * Specifies an array of Mailbox elements.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayofLegacyDNsType
     */
    public $Mailboxes;

    /**
     * Whether or not to search archive folders only.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $SearchArchiveOnly;
}
