<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindItemResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single FindItem operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FindItemResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the results from a search of a single root folder during a
     * FindItem operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FindItemParentType
     */
    public $RootFolder;
}
