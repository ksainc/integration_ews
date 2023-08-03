<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\PerformReminderActionResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a PerformReminderAction request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class PerformReminderActionResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the identifiers of updated reminder items.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemIdsType
     */
    public $UpdatedItemIds;
}
