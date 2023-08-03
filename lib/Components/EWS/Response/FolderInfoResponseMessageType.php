<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FolderInfoResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single CopyFolder operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FolderInfoResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of copied folders.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfFoldersType
     */
    public $Folders;
}
