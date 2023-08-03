<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\ExportItemsType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to export items from a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class ExportItemsType extends BaseRequestType
{
    /**
     * Contains an array of item identifiers that identify the items to export
     * from a mailbox.
     *
     * @since Exchange 2010 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemIdsType
     */
    public $ItemIds;
}
