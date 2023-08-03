<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindFolderResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single FindFolder operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FindFolderResponseMessageType extends ResponseMessageType
{
    /**
     * Contains the results from a search of a single root folder during a
     * FindFolder operation.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FindFolderParentType
     */
    public $RootFolder;
}
