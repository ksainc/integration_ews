<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\SyncFolderHierarchyResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single SyncFolderHierarchy operation
 * request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class SyncFolderHierarchyResponseMessageType extends ResponseMessageType
{
    /**
     * Contains a sequence array of change types that represent the types of
     * differences between the items on the client and the items on the Exchange
     * server.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\SyncFolderHierarchyChangesType
     */
    public $Changes;

    /**
     * Indicates whether the last item to synchronize has been included in the
     * response.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IncludesLastFolderInRange;

    /**
     * Contains a base64-encoded form of the synchronization data that is
     * updated after each successful request.
     *
     * This is used to identify the synchronization state.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $SyncState;
}
