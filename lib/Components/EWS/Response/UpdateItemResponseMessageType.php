<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\UpdateItemResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a single UpdateItem request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class UpdateItemResponseMessageType extends ItemInfoResponseMessageType
{
    /**
     * Contains the number of conflicts in an UpdateItem response.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ConflictResultsType
     */
    public $ConflictResults;
}
